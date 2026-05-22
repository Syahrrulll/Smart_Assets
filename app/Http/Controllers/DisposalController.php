<?php

namespace App\Http\Controllers;

use App\Models\Disposal;
use App\Models\Item;
use Illuminate\Http\Request;

class DisposalController extends Controller
{
    public function index()
    {
        $disposals = Disposal::with('item')->latest()->paginate(10);
        return view('disposals.index', compact('disposals'));
    }

    public function create()
    {
        $items = Item::all();
        return view('disposals.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'reason' => 'required|string',
            'approved_by' => 'nullable|string|max:255',
        ]);

        Disposal::create([
            'item_id' => $request->item_id,
            'reason' => $request->reason,
            'approved_by' => $request->approved_by,
            'status' => 'pending',
        ]);

        return redirect()->route('disposals.index')->with('success', 'Pengajuan penghapusan aset berhasil dibuat.');
    }

    public function edit(Disposal $disposal)
    {
        return view('disposals.edit', compact('disposal'));
    }

    public function update(Request $request, Disposal $disposal)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,disposed',
            'disposal_date' => 'nullable|date',
        ]);

        $disposal->update($request->only('status', 'disposal_date'));

        return redirect()->route('disposals.index')->with('success', 'Status penghapusan aset diperbarui.');
    }

    public function destroy(Disposal $disposal)
    {
        $disposal->delete();
        return redirect()->route('disposals.index')->with('success', 'Data penghapusan dihapus.');
    }
}
