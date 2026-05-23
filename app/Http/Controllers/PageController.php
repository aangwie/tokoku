<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller untuk menampilkan halaman statis seperti
 * Syarat & Ketentuan dan Kebijakan Pengembalian Dana.
 */
class PageController extends Controller
{
    /**
     * Tampilkan halaman Syarat & Ketentuan.
     *
     * @return View
     */
    public function terms(): View
    {
        $content = Setting::get('terms_and_conditions', $this->getDefaultTerms());
        
        return view('pages.terms', compact('content'));
    }

    /**
     * Tampilkan halaman Kebijakan Pengembalian Dana.
     *
     * @return View
     */
    public function refundPolicy(): View
    {
        $content = Setting::get('refund_policy', $this->getDefaultRefundPolicy());
        
        return view('pages.refund-policy', compact('content'));
    }

    /**
     * Default content untuk Syarat & Ketentuan jika belum diset.
     *
     * @return string
     */
    private function getDefaultTerms(): string
    {
        return '<h2>Syarat & Ketentuan</h2><p>Halaman ini belum dikonfigurasi. Silakan hubungi administrator.</p>';
    }

    /**
     * Default content untuk Kebijakan Pengembalian Dana jika belum diset.
     *
     * @return string
     */
    private function getDefaultRefundPolicy(): string
    {
        return '<h2>Kebijakan Pengembalian Dana</h2><p>Halaman ini belum dikonfigurasi. Silakan hubungi administrator.</p>';
    }
}