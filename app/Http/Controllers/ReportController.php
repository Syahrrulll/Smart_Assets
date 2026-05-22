<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Report;

class ReportController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('staff.dashboard')->with('error', 'Akses ditolak.');
        }

        $reports = Report::with(['user', 'item'])->latest()->get();
        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_laporan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'item_id' => 'nullable|exists:items,id'
        ]);

        Report::create([
            'user_id' => Auth::id(),
            'item_id' => $request->item_id,
            'judul_laporan' => $request->judul_laporan,
            'deskripsi' => $request->deskripsi,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil dikirim ke Admin. Terima kasih!');
    }

    public function update(Request $request, Report $report)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('staff.dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'status' => 'required|in:pending,diproses,selesai'
        ]);

        $report->update(['status' => $request->status]);

        return redirect()->route('reports.index')->with('success', 'Status laporan berhasil diperbarui.');
    }
}
