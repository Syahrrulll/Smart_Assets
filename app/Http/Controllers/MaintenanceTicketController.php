<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceTicket;
use App\Models\Item;
use Illuminate\Http\Request;

class MaintenanceTicketController extends Controller
{
    public function index()
    {
        $tickets = MaintenanceTicket::with('item')->latest()->paginate(10);
        return view('maintenance_tickets.index', compact('tickets'));
    }

    public function create()
    {
        $items = Item::all();
        return view('maintenance_tickets.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'reported_by' => 'nullable|string|max:255',
            'issue_description' => 'required|string',
        ]);

        MaintenanceTicket::create([
            'item_id' => $request->item_id,
            'reported_by' => $request->reported_by,
            'issue_description' => $request->issue_description,
            'status' => 'open',
        ]);

        return redirect()->route('maintenance-tickets.index')->with('success', 'Tiket perbaikan berhasil dibuat.');
    }

    public function edit(MaintenanceTicket $maintenanceTicket)
    {
        return view('maintenance_tickets.edit', compact('maintenanceTicket'));
    }

    public function update(Request $request, MaintenanceTicket $maintenanceTicket)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved',
            'cost' => 'nullable|numeric',
            'resolved_at' => 'nullable|date',
        ]);

        $maintenanceTicket->update($request->only('status', 'cost', 'resolved_at'));

        return redirect()->route('maintenance-tickets.index')->with('success', 'Status tiket diperbarui.');
    }

    public function destroy(MaintenanceTicket $maintenanceTicket)
    {
        $maintenanceTicket->delete();
        return redirect()->route('maintenance-tickets.index')->with('success', 'Tiket perbaikan dihapus.');
    }
}
