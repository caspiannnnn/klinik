<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResepsionisQrScanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->role !== 'resepsionis') {
            abort(403);
        }

        return view('resepsionis.qr_scan');
    }

    public function process(Request $request)
    {
        if (Auth::user()->role !== 'resepsionis') {
            abort(403);
        }

        $request->validate([
            'qr_input' => 'required|string',
        ]);

        $input = trim($request->qr_input);

        // Input bisa berupa:
        // 1) token langsung
        // 2) URL penuh hasil scan: http://127.0.0.1:8000/scan/pasien/{token}
        $token = $input;

        // Kalau input berupa URL, ambil token bagian terakhir
        if (filter_var($input, FILTER_VALIDATE_URL)) {
            $parts = parse_url($input);
            $path = $parts['path'] ?? '';
            $segments = array_values(array_filter(explode('/', $path)));
            $token = end($segments) ?: $input;
        }

        return redirect()->route('pasien.scan', $token);
    }
}
