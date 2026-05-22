<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use App\Models\CustomerBankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerSettingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan pelanggan.
     */
    public function index()
    {
        $user = Auth::user();
        $addresses = $user->customerAddresses()->orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();
        $bankAccounts = $user->customerBankAccounts()->orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();

        return view('customer.settings', compact('addresses', 'bankAccounts'));
    }

    /**
     * Simpan alamat pengiriman baru.
     */
    public function storeAddress(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:50',
            'recipient_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'full_address' => 'required|string|max:500',
            'is_default' => 'nullable|boolean',
        ]);

        $user = Auth::user();

        // Jika set as default, hapus default yang lama
        if ($request->is_default) {
            $user->customerAddresses()->update(['is_default' => false]);
        }

        // Jika belum ada alamat sama sekali, jadikan default otomatis
        $isFirst = $user->customerAddresses()->count() === 0;

        $user->customerAddresses()->create([
            'label' => $request->label,
            'recipient_name' => $request->recipient_name,
            'phone' => $request->phone,
            'full_address' => $request->full_address,
            'is_default' => $request->is_default || $isFirst,
        ]);

        return redirect()->route('customer.settings')->with('success', 'Alamat pengiriman berhasil ditambahkan!');
    }

    /**
     * Update alamat pengiriman.
     */
    public function updateAddress(Request $request, CustomerAddress $address)
    {
        // Pastikan alamat milik user yang login
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'label' => 'required|string|max:50',
            'recipient_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'full_address' => 'required|string|max:500',
            'is_default' => 'nullable|boolean',
        ]);

        if ($request->is_default) {
            Auth::user()->customerAddresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update([
            'label' => $request->label,
            'recipient_name' => $request->recipient_name,
            'phone' => $request->phone,
            'full_address' => $request->full_address,
            'is_default' => (bool) $request->is_default,
        ]);

        return redirect()->route('customer.settings')->with('success', 'Alamat pengiriman berhasil diperbarui!');
    }

    /**
     * Hapus alamat pengiriman.
     */
    public function destroyAddress(CustomerAddress $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $wasDefault = $address->is_default;
        $address->delete();

        // Jika yang dihapus adalah default, set alamat pertama sebagai default
        if ($wasDefault) {
            $first = Auth::user()->customerAddresses()->first();
            if ($first) {
                $first->update(['is_default' => true]);
            }
        }

        return redirect()->route('customer.settings')->with('success', 'Alamat pengiriman berhasil dihapus!');
    }

    /**
     * Simpan rekening bank baru.
     */
    public function storeBankAccount(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:100',
            'is_default' => 'nullable|boolean',
        ]);

        $user = Auth::user();

        if ($request->is_default) {
            $user->customerBankAccounts()->update(['is_default' => false]);
        }

        $isFirst = $user->customerBankAccounts()->count() === 0;

        $user->customerBankAccounts()->create([
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
            'is_default' => $request->is_default || $isFirst,
        ]);

        return redirect()->route('customer.settings')->with('success', 'Rekening bank berhasil ditambahkan!');
    }

    /**
     * Update rekening bank.
     */
    public function updateBankAccount(Request $request, CustomerBankAccount $bankAccount)
    {
        if ($bankAccount->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:100',
            'is_default' => 'nullable|boolean',
        ]);

        if ($request->is_default) {
            Auth::user()->customerBankAccounts()->where('id', '!=', $bankAccount->id)->update(['is_default' => false]);
        }

        $bankAccount->update([
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
            'is_default' => (bool) $request->is_default,
        ]);

        return redirect()->route('customer.settings')->with('success', 'Rekening bank berhasil diperbarui!');
    }

    /**
     * Hapus rekening bank.
     */
    public function destroyBankAccount(CustomerBankAccount $bankAccount)
    {
        if ($bankAccount->user_id !== Auth::id()) {
            abort(403);
        }

        $wasDefault = $bankAccount->is_default;
        $bankAccount->delete();

        if ($wasDefault) {
            $first = Auth::user()->customerBankAccounts()->first();
            if ($first) {
                $first->update(['is_default' => true]);
            }
        }

        return redirect()->route('customer.settings')->with('success', 'Rekening bank berhasil dihapus!');
    }

    /**
     * Perbarui data profil pelanggan (nama, no hp, password).
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        $user->name = $request->name;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('customer.settings')->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
