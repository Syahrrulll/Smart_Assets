<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Item;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::with('item')->latest()->paginate(10);
        return view('borrowings.index', compact('borrowings'));
    }

    public function create()
    {
        $items = Item::all();
        return view('borrowings.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'borrower_name' => 'required|string|max:255',
            'expected_return_at' => 'nullable|date',
        ]);

        Borrowing::create([
            'item_id' => $request->item_id,
            'borrower_name' => $request->borrower_name,
            'expected_return_at' => $request->expected_return_at,
            'status' => 'borrowed',
        ]);

        return redirect()->route('borrowings.index')->with('success', 'Peminjaman berhasil dicatat.');
    }

    public function edit(Borrowing $borrowing)
    {
        return view('borrowings.edit', compact('borrowing'));
    }

    public function update(Request $request, Borrowing $borrowing)
    {
        $request->validate([
            'status' => 'required|in:borrowed,returned,overdue',
            'returned_at' => 'nullable|date',
        ]);

        $borrowing->update($request->only('status', 'returned_at'));

        return redirect()->route('borrowings.index')->with('success', 'Status peminjaman diperbarui.');
    }

    public function destroy(Borrowing $borrowing)
    {
        $borrowing->delete();
        return redirect()->route('borrowings.index')->with('success', 'Data peminjaman dihapus.');
    }
}
