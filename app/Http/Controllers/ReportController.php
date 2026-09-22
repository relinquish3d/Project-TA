<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Menyimpan laporan baru dari user
     */
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

            // 3. Simpan data ke database dengan status awal in_progress
            $report = Report::create([
                'user_id' => Auth::id(), // ID user yang sedang login
                'category' => $request->category,
                'description' => $request->description,
                'attachment' => $attachmentPath,
                'status' => 'in_progress',
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan berhasil dikirim! Tiket: ' . $report->ticket_code,
                    'report' => $report,
                ]);
            }

            return redirect()->back()->with('success_report', 'Laporan berhasil dikirim! Tiket: ' . $report->ticket_code);

        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan: ' . $e->getMessage(),
                ], 422);
            }
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan daftar tiket laporan untuk user (Inbox)
     */
    public function inbox(Request $request)
    {
        $status = $request->query('status'); // all, in_progress, completed
        $user = Auth::user();

        $query = $user->reports()->latest();

        if ($status && in_array($status, ['in_progress', 'completed'])) {
            $query->where('status', $status);
        }

        $reports = $query->get();
        $inProgressCount = $user->reports()->where('status', 'in_progress')->count();
        $completedCount = $user->reports()->where('status', 'completed')->count();
        $totalCount = $user->reports()->count();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'reports' => $reports->map(function ($report) {
                    return [
                        'id' => $report->id,
                        'ticket_code' => $report->ticket_code,
                        'category' => $report->category,
                        'category_label' => $report->category_label,
                        'description' => $report->description,
                        'status' => $report->status,
                        'status_label' => $report->status_label,
                        'status_badge_class' => $report->status_badge_class,
                        'attachment_url' => $report->attachment ? asset('storage/' . $report->attachment) : null,
                        'admin_notes' => $report->admin_notes,
                        'created_at' => $report->created_at ? $report->created_at->format('d M Y, H:i') : '-',
                        'created_at_human' => $report->created_at ? $report->created_at->diffForHumans() : '-',
                    ];
                }),
                'counts' => [
                    'all' => $totalCount,
                    'in_progress' => $inProgressCount,
                    'completed' => $completedCount,
                ]
            ]);
        }

        return view('inbox', compact('reports', 'inProgressCount', 'completedCount', 'totalCount', 'status'));
    }

    /**
     * Memperbarui status tiket laporan (Admin / Staff)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:in_progress,completed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $report = Report::findOrFail($id);
        $report->status = $request->status;
        if ($request->has('admin_notes')) {
            $report->admin_notes = $request->admin_notes;
        }
        $report->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status laporan berhasil diperbarui!',
                'report' => $report,
            ]);
        }

        return back()->with('success', 'Status tiket laporan #' . $report->ticket_code . ' berhasil diperbarui!');
    }
}