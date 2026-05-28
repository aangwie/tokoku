<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Services\MidtransService;
use App\Services\XenditService;
use App\Services\PaymentService;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    protected $midtransService;
    protected $xenditService;
    protected $shippingService;

    public function __construct(
        MidtransService $midtransService, 
        XenditService $xenditService,
        ShippingService $shippingService
    ) {
        $this->midtransService = $midtransService;
        $this->xenditService = $xenditService;
        $this->shippingService = $shippingService;
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        $subtotal = 0;
        $totalWeight = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            $totalWeight += $item['weight'] * $item['quantity'];
        }

        // Hitung Ongkos Kirim - cek apakah ada produk yang memerlukan ongkir berbayar
        $shippingCost = 0;
        $hasNonFreeShipping = false;
        
        if (count($cart) > 0) {
            // Cek setiap produk di cart apakah ada yang berbayar ongkir
            foreach ($cart as $productId => $item) {
                $product = Product::find($productId);
                if ($product && !$product->is_free_shipping) {
                    $hasNonFreeShipping = true;
                    break;
                }
            }
            
            // Jika ada produk berbayar ongkir, gunakan fixed cost sebagai estimasi
            // (Ongkir sebenarnya akan dihitung saat checkout berdasarkan alamat)
            if ($hasNonFreeShipping) {
                $shippingCost = (float) Setting::get('shipping_cost', '0');
            }
        }

        // Terapkan kupon jika ada di sesi
        $discountAmount = 0;
        $coupon = session()->get('coupon');
        if ($coupon) {
            // Re-validasi minimal belanja
            if ($subtotal >= $coupon['min_order']) {
                if ($coupon['type'] === 'percentage') {
                    $discountAmount = ($subtotal * $coupon['value']) / 100;
                } else {
                    $discountAmount = $coupon['value'];
                }
                // Diskon tidak boleh melebihi subtotal
                $discountAmount = min($discountAmount, $subtotal);
            } else {
                session()->forget('coupon');
                $coupon = null;
            }
        }

        $totalPrice = $subtotal - $discountAmount + $shippingCost;

        return view('cart.index', compact('cart', 'subtotal', 'totalWeight', 'shippingCost', 'discountAmount', 'totalPrice', 'coupon'));
    }

    public function add(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);

        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi!');
        }

        $cart = session()->get('cart', []);

        // Jika produk sudah ada di keranjang, tambahkan jumlahnya
        if (isset($cart[$product->id])) {
            if ($product->stock < ($cart[$product->id]['quantity'] + $quantity)) {
                return redirect()->back()->with('error', 'Total jumlah di keranjang melebihi stok tersedia!');
            }
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            // Tambahkan produk baru ke keranjang
            $cart[$product->id] = [
                'name' => $product->name,
                'quantity' => $quantity,
                'price' => $product->price,
                'weight' => $product->weight,
                'image' => $product->image,
                'slug' => $product->slug,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($id);
        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan!');
        }

        if ($product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi untuk jumlah ini!');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diperbarui!');
        }

        return redirect()->route('cart.index')->with('error', 'Produk tidak ada di keranjang!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang!');
    }

    public function clear()
    {
        session()->forget('cart');
        session()->forget('coupon');
        
        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan!');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon) {
            return redirect()->back()->with('error', 'Kode kupon tidak valid!');
        }

        // Cek masa berlaku
        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return redirect()->back()->with('error', 'Kupon ini sudah kedaluwarsa!');
        }

        // Cek kuota
        if ($coupon->max_uses && $coupon->used_count >= $coupon->max_uses) {
            return redirect()->back()->with('error', 'Kuota penggunaan kupon ini sudah habis!');
        }

        // Cek minimal belanja
        $cart = session()->get('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        if ($subtotal < $coupon->min_order) {
            return redirect()->back()->with('error', 'Minimal belanja untuk kupon ini adalah Rp ' . number_format($coupon->min_order, 0, ',', '.'));
        }

        // Simpan kupon ke session
        session()->put('coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'min_order' => $coupon->min_order,
        ]);

        return redirect()->route('cart.index')->with('success', 'Kupon diskon berhasil diterapkan!');
    }

    public function removeCoupon()
    {
        session()->forget('coupon');
        return redirect()->route('cart.index')->with('success', 'Kupon diskon dihapus!');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (count($cart) === 0) {
            return redirect()->route('home')->with('error', 'Keranjang belanja Anda kosong!');
        }

        return view('cart.checkout');
    }

    public function processCheckout(Request $request)
    {
        // Dynamically determine allowed gateways based on store setting
        $paymentMethodSetting = Setting::get('payment_method', 'paymentgateway');
        $allowedGateways = $paymentMethodSetting === 'transfer' ? 'transfer' : 'midtrans,xendit';

        $request->validate([
            'shipping_address' => 'required|string',
            'city_code' => 'nullable|string',
            'payment_gateway' => 'required|in:' . $allowedGateways,
        ]);

        $cart = session()->get('cart', []);
        if (count($cart) === 0) {
            return redirect()->route('home')->with('error', 'Keranjang belanja kosong!');
        }

        // Hitung ulang total di backend untuk keamanan
        $subtotal = 0;
        $totalWeight = 0;
        
        // Prepare cart items with product details for shipping calculation
        $cartItemsWithDetails = [];
        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            $cartItemsWithDetails[] = [
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'weight' => $item['weight'],
                'is_free_shipping' => $product ? $product->is_free_shipping : true,
            ];
            
            $subtotal += $item['price'] * $item['quantity'];
            $totalWeight += $item['weight'] * $item['quantity'];
        }

        // Hitung ongkos kirim - gunakan API jika city_code tersedia, jika tidak gunakan fixed cost
        $shippingCost = 0;
        if ($request->city_code && $this->shippingService->isConfigured()) {
            // Gunakan ShippingService untuk perhitungan dinamis
            $shippingCost = $this->shippingService->calculateCartShipping(
                $cartItemsWithDetails,
                $request->city_code
            );
            
            // Jika API gagal atau return 0, fallback ke fixed cost
            if ($shippingCost == 0) {
                $shippingCost = (float) Setting::get('shipping_cost', '0');
            }
        } else {
            // Fallback ke ongkos kirim fixed dari pengaturan admin
            $shippingCost = (float) Setting::get('shipping_cost', '0');
        }

        $discountAmount = 0;
        $coupon = session()->get('coupon');
        $couponModel = null;
        if ($coupon) {
            $couponModel = Coupon::find($coupon['id']);
            if ($couponModel && ($subtotal >= $couponModel->min_order)) {
                if ($couponModel->type === 'percentage') {
                    $discountAmount = ($subtotal * $couponModel->value) / 100;
                } else {
                    $discountAmount = $couponModel->value;
                }
                $discountAmount = min($discountAmount, $subtotal);
            }
        }

        $totalPrice = $subtotal - $discountAmount + $shippingCost;
        $orderNumber = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        // Buat Order
        $order = Order::create([
            'user_id' => Auth::id(),
            'coupon_id' => $couponModel ? $couponModel->id : null,
            'order_number' => $orderNumber,
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'total_price' => $totalPrice,
            'shipping_cost' => $shippingCost,
            'status' => 'pending',
            'payment_gateway' => $request->payment_gateway,
            'shipping_address' => $request->shipping_address,
        ]);

        // Buat Order Items & Kurangi Stok Produk
        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Kurangi stok produk
            $product = Product::find($productId);
            if ($product) {
                $product->decrement('stock', $item['quantity']);
            }
        }

        // Update pemakaian kupon jika digunakan
        if ($couponModel) {
            $couponModel->increment('used_count');
        }

        // Bersihkan Keranjang & Kupon di Sesi
        session()->forget('cart');
        session()->forget('coupon');

        // Proses berdasarkan metode pembayaran
        if ($request->payment_gateway === 'transfer') {
            // Transfer Bank Manual - tidak perlu API gateway
            $order->update(['payment_reference' => null]);
            return redirect()->route('orders.show', $order->id)->with('success', 'Pesanan berhasil dibuat! Silakan lakukan transfer bank sesuai instruksi di halaman detail pesanan.');
        }

        // Proses Pembayaran Otomatis via API Gateway
        try {
            if ($request->payment_gateway === 'midtrans') {
                // Buat Snap Token
                $snapToken = $this->midtransService->createSnapToken($order);
                $order->update(['payment_reference' => $snapToken]);
                return redirect()->route('orders.show', $order->id)->with('success', 'Pesanan dibuat. Silakan selesaikan pembayaran!');
            } else {
                // Xendit
                $invoiceUrl = $this->xenditService->createInvoice($order);
                $order->update(['payment_reference' => $invoiceUrl]);
                return redirect()->away($invoiceUrl);
            }
        } catch (\Exception $e) {
            // Jika gateway gagal, simpan sebagai pending dan arahkan ke detail pesanan
            return redirect()->route('orders.show', $order->id)->with('error', 'Gagal memproses pembayaran otomatis: ' . $e->getMessage() . '. Harap hubungi admin.');
        }
    }
}
