<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        // Optimasi: Gunakan eager loading 'category' untuk menghindari N+1 query
        $products = Product::with('category')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'weight' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:512',
            'is_free_shipping' => 'required|boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->imageService->compressAndSave($request->file('image'), 'products');
        }

        Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $request->slug ? Str::slug($request->slug) : Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'weight' => $request->weight,
            'stock' => $request->stock,
            'image' => $imagePath,
            'is_free_shipping' => $request->is_free_shipping,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dibuat!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'weight' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:512',
            'is_free_shipping' => 'required|boolean',
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                $oldPath = str_replace('storage/', '', $product->image);
                Storage::disk('public')->delete($oldPath);
            }
            $imagePath = $this->imageService->compressAndSave($request->file('image'), 'products');
        }

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $request->slug ? Str::slug($request->slug) : Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'weight' => $request->weight,
            'stock' => $request->stock,
            'image' => $imagePath,
            'is_free_shipping' => $request->is_free_shipping,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        // Delete image if exists
        if ($product->image) {
            $oldPath = str_replace('storage/', '', $product->image);
            Storage::disk('public')->delete($oldPath);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
