<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request)
    {
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

        // 3. Simpan data ke database (Sesuaikan dengan Model Report Anda)
        /*
        Report::create([
            'user_id' => auth()->id(),
            'category' => $request->category,
            'description' => $request->description,
            'attachment' => $attachmentPath,
        ]);
        */

        // 4. Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Laporan Anda berhasil dikirim dan akan segera ditinjau.');
    }
}