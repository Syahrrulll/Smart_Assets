<?php

namespace App\Http\Controllers;

use App\Models\Relocation;
use App\Models\Item;
use Illuminate\Http\Request;

class RelocationController extends Controller
{
    public function index()
    {
        $relocations = Relocation::with('item')->latest()->paginate(10);
        return view('relocations.index', compact('relocations'));
    }

    public function create()
    {
        $items = Item::all();
        // Here you might want to fetch unique locations from DB or a config list. 
        // For simplicity, we just pass the items.
        return view('relocations.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'from_location' => 'nullable|string',
            'to_location' => 'required|string',
            'requested_by' => 'nullable|string',
        ]);

        Relocation::create([
            'item_id' => $request->item_id,
            'from_location' => $request->from_location,
            'to_location' => $request->to_location,
            'requested_by' => $request->requested_by,
            'status' => 'pending',
        ]);

        return redirect()->route('relocations.index')->with('success', 'Pengajuan relokasi berhasil dibuat.');
    }

    public function edit(Relocation $relocation)
    {
        return view('relocations.edit', compact('relocation'));
    }

    public function update(Request $request, Relocation $relocation)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'approved_by' => 'nullable|string',
        ]);

        $relocation->update($request->only('status', 'approved_by'));

        // If approved, update the item's location automatically
        if ($request->status === 'approved') {
            $relocation->item->update(['lokasi_barang' => $relocation->to_location]);
        }

        return redirect()->route('relocations.index')->with('success', 'Status relokasi diperbarui.');
    }

    public function destroy(Relocation $relocation)
    {
        $relocation->delete();
        return redirect()->route('relocations.index')->with('success', 'Data relokasi dihapus.');
    }
}
