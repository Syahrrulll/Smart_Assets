<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    // Proteksi: Hanya Admin yang boleh kelola lokasi
    private function checkAdmin() {
        if (Auth::check() && Auth::user()->role !== 'admin') {
            return false;
        }
        return true;
    }

    public function index()
    {
        if (!$this->checkAdmin()) return redirect()->route('items.index')->with('error', 'Akses Ditolak');

        $locations = Location::orderBy('nama_lokasi')->get();
        return view('locations.index', compact('locations'));
    }

    public function store(Request $request)
    {
        if (!$this->checkAdmin()) return redirect()->route('items.index');

        $request->validate([
            'nama_lokasi' => 'required|unique:locations,nama_lokasi|max:255'
        ]);

        Location::create($request->all());

        return back()->with('success', 'Lokasi baru berhasil ditambahkan.');
    }

    public function destroy(Location $location)
    {
        if (!$this->checkAdmin()) return redirect()->route('items.index');

        $location->delete();
        return back()->with('success', 'Lokasi berhasil dihapus.');
    }
}
