<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        try {
            // 1. Validasi input dari form
            $request->validate([
                'category' => 'required|string',
                'description' => 'required|string|max:1000',
                'attachment' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
            ]);

            // 2. Tangani upload file jika ada
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                // Menyimpan file ke folder storage/app/public/reports
                $attachmentPath = $request->file('attachment')->store('reports', 'public');
            }

            // 3. Simpan data ke database
            Report::create([
                'user_id' => Auth::id(), // ID user yang sedang login
                'category' => $request->category,
                'description' => $request->description,
                'attachment' => $attachmentPath,
            ]);

            // 4. Redirect kembali dengan pesan sukses
            return redirect()->back()->with('success', 'Laporan Anda berhasil dikirim dan akan segera ditinjau.');

        } catch (\Exception $e) {
            // JIKA MASIH GAGAL, HALAMAN INI AKAN MEMUNCULKAN PESAN ERROR ASLINYA
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }
}