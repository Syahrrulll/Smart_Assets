<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemApiController extends Controller
{
    /**
     * Get a list of all assets for external ERP integration (SIMAK-BMN).
     */
    public function index(Request $request)
    {
        $query = Item::query();

        if ($request->has('updated_since')) {
            $query->where('updated_at', '>=', $request->updated_since);
        }

        $items = $query->paginate(100);

        return response()->json([
            'status' => 'success',
            'data' => $items,
        ]);
    }

    /**
     * Get specific asset details.
     */
    public function show($kode_barang)
    {
        $item = Item::where('kode_barang', $kode_barang)->first();

        if (!$item) {
            return response()->json(['status' => 'error', 'message' => 'Asset not found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $item,
        ]);
    }
}
