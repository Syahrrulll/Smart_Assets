<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Location;
use App\Models\ItemHistory;
use Illuminate\Http\Request;
use App\Imports\ItemsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ItemController extends Controller
{
    private function enforceStrictQrMode(Request $request)
    {
        if (Auth::user()->role !== 'admin' && !$request->has('scanned_from_qr')) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return true;
        }
        return false;
    }

    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return view('staff.dashboard');
        }

        $query = Item::query();

        if ($request->filled('lokasi')) $query->where('lokasi_barang', $request->lokasi);
        if ($request->filled('tahun')) $query->where('tahun_barang', $request->tahun);
        if ($request->filled('kondisi')) $query->where('kondisi_barang', $request->kondisi);
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('nama_barang', 'like', "%{$keyword}%")
                  ->orWhere('kode_barang', 'like', "%{$keyword}%")
                  ->orWhere('nup', 'like', "%{$keyword}%");
            });
        }

        $sortOption = $request->input('sort_option', 'created_at_desc');
        switch ($sortOption) {
            case 'nup_asc': $query->orderBy('nup', 'asc'); break;
            case 'nup_desc': $query->orderBy('nup', 'desc'); break;
            case 'nama_asc': $query->orderBy('nama_barang', 'asc'); break;
            case 'nama_desc': $query->orderBy('nama_barang', 'desc'); break;
            case 'created_at_desc': default: $query->orderBy('created_at', 'desc'); break;
        }

        $items = $query->paginate(250)->withQueryString();

        $list_lokasi = Cache::remember('list_lokasi', 3600, function () {
            return Location::orderBy('nama_lokasi')->pluck('nama_lokasi');
        });

        $list_tahun = Cache::remember('list_tahun', 3600, function () {
            return Item::select('tahun_barang')->whereNotNull('tahun_barang')->distinct()->orderBy('tahun_barang', 'desc')->pluck('tahun_barang');
        });

        $global_activities = ItemHistory::with('item')->latest()->take(50)->get();

        // =================================================================
        // FITUR CERDAS 1: GHOST ASSET DETECTOR (Barang > 6 Bulan Tidak Diupdate)
        // =================================================================
        $ghost_assets = Cache::remember('ghost_assets', 3600, function () {
            $sixMonthsAgo = Carbon::now()->subMonths(6);
            return Item::where('updated_at', '<', $sixMonthsAgo)->get();
        });

        // =================================================================
        // FITUR CERDAS 2: SMART INSIGHT (Rekomendasi Pengadaan jika Rusak > 30%)
        // =================================================================
        $smart_insights = Cache::remember('smart_insights', 3600, function () {
            $item_stats = Item::selectRaw('nama_barang, count(*) as total, sum(case when kondisi_barang = "Rusak Berat" then 1 else 0 end) as rusak_berat')
                ->groupBy('nama_barang')
                ->havingRaw('total > 2')
                ->get();

            $insights = [];
            foreach($item_stats as $stat) {
                $percent = ($stat->rusak_berat / $stat->total) * 100;
                if($percent >= 30) {
                    $insights[] = [
                        'nama' => $stat->nama_barang,
                        'total' => $stat->total,
                        'rusak' => $stat->rusak_berat,
                        'persen' => round($percent, 1)
                    ];
                }
            }
            return $insights;
        });

        // =================================================================
        // FITUR CERDAS 3: DEPRECIATION TRACKING (Total Nilai Aset)
        // =================================================================
        $financial_stats = Cache::remember('financial_stats', 3600, function () {
            $allItems = Item::whereNotNull('harga_beli')->get();
            $total_awal = 0;
            $total_saat_ini = 0;

            foreach($allItems as $item) {
                $total_awal += $item->harga_beli;
                $total_saat_ini += $item->nilai_buku; // Menggunakan accessor
            }

            return [
                'total_awal' => $total_awal,
                'total_saat_ini' => $total_saat_ini,
                'penyusutan' => $total_awal - $total_saat_ini
            ];
        });

        // =================================================================
        // FITUR CERDAS 4: PREVENTIVE MAINTENANCE ALERTS
        // =================================================================
        $maintenance_alerts = Cache::remember('maintenance_alerts', 3600, function () {
            $items = Item::whereNotNull('maintenance_interval_months')->get();
            $alerts = [];
            foreach ($items as $item) {
                $baseDate = $item->last_maintenance_date ? Carbon::parse($item->last_maintenance_date) : $item->created_at;
                $dueDate = $baseDate->copy()->addMonths($item->maintenance_interval_months);
                
                // Peringatkan jika sudah jatuh tempo ATAU sisa waktu < 14 hari
                if (Carbon::now()->addDays(14)->greaterThanOrEqualTo($dueDate)) {
                    $alerts[] = [
                        'item' => $item,
                        'due_date' => $dueDate,
                        'is_overdue' => Carbon::now()->greaterThan($dueDate)
                    ];
                }
            }
            return collect($alerts)->sortBy('due_date')->take(10);
        });

        return view('items.index', compact('items', 'list_lokasi', 'list_tahun', 'global_activities', 'ghost_assets', 'smart_insights', 'financial_stats', 'maintenance_alerts'));
    }

    public function create(Request $request)
    {
        if ($this->enforceStrictQrMode($request)) return redirect()->route('login');

        $locations = Location::orderBy('nama_lokasi')->get();
        $next_nup = (Item::max('nup') ?? 0) + 1;

        return view('items.create', compact('locations', 'next_nup'));
    }

    public function store(Request $request)
    {
        if ($this->enforceStrictQrMode($request)) return redirect()->route('login');

        $request->validate([
            'nama_barang' => 'required',
            'kode_barang' => 'required|unique:items',
            'nup' => 'nullable|integer',
            'tahun_barang' => 'nullable|integer',
            'lokasi_barang' => 'nullable',
            'kondisi_barang' => 'nullable',
            'harga_beli' => 'nullable|numeric',
            'maintenance_interval_months' => 'nullable|integer',
            'foto_barang' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $input = $request->all();

        if (empty($input['nup'])) {
            $input['nup'] = (Item::max('nup') ?? 0) + 1;
        }

        if ($request->hasFile('foto_barang')) {
            $imageName = time().'.jpg';
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('foto_barang')->getRealPath());
            $image->scaleDown(width: 1024);
            $image->toJpeg(70)->save(public_path('images/items/'.$imageName));
            $input['foto_barang'] = 'images/items/'.$imageName;
        }

        $item = Item::create($input);

        ItemHistory::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'field_name' => 'Data Baru',
            'old_value' => '-',
            'new_value' => 'Barang Ditambahkan'
        ]);

        Cache::forget('financial_stats');
        Cache::forget('maintenance_alerts');

        return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Request $request, Item $item)
    {
        $locations = Location::orderBy('nama_lokasi')->get();
        $histories = $item->histories;

        $is_scanned = $request->has('scanned_from_qr') ? true : false;

        return view('items.edit', compact('item', 'locations', 'histories', 'is_scanned'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'nama_barang' => 'required',
            'kode_barang' => 'required|unique:items,kode_barang,'.$item->id,
            'nup' => 'nullable|integer',
            'tahun_barang' => 'nullable|integer',
            'lokasi_barang' => 'nullable',
            'kondisi_barang' => 'nullable',
            'harga_beli' => 'nullable|numeric',
            'maintenance_interval_months' => 'nullable|integer',
            'foto_barang' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $input = $request->all();

        if (empty($input['nup'])) {
            $input['nup'] = $item->nup;
        }

        if ($request->hasFile('foto_barang')) {
            if ($item->foto_barang && File::exists(public_path($item->foto_barang))) {
                File::delete(public_path($item->foto_barang));
            }
            $imageName = time().'.jpg';
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('foto_barang')->getRealPath());
            $image->scaleDown(width: 1024);
            $image->toJpeg(70)->save(public_path('images/items/'.$imageName));
            $input['foto_barang'] = 'images/items/'.$imageName;
        }

        $fieldsToTrack = ['nama_barang', 'kode_barang', 'nup', 'tahun_barang', 'lokasi_barang', 'kondisi_barang', 'harga_beli', 'maintenance_interval_months'];
        foreach ($fieldsToTrack as $field) {
            if (isset($input[$field]) && $item->$field != $input[$field]) {
                ItemHistory::create([
                    'item_id' => $item->id,
                    'user_id' => Auth::id(),
                    'user_name' => Auth::user()->name,
                    'field_name' => $field,
                    'old_value' => (string) $item->$field,
                    'new_value' => (string) $input[$field]
                ]);
            }
        }

        $item->update($input);

        Cache::forget('financial_stats');
        Cache::forget('maintenance_alerts');

        if (Auth::user()->role !== 'admin') {
            return redirect()->route('items.edit', ['item' => $item->id, 'scanned_from_qr' => 1])
                             ->with('success', 'Kondisi / Lokasi barang berhasil diperbarui!');
        }

        return redirect()->route('items.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Request $request, Item $item)
    {
        if ($this->enforceStrictQrMode($request)) return redirect()->route('login');

        if ($item->foto_barang && File::exists(public_path($item->foto_barang))) {
            File::delete(public_path($item->foto_barang));
        }
        $item->delete();

        Cache::forget('financial_stats');
        Cache::forget('maintenance_alerts');

        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        if ($this->enforceStrictQrMode($request)) return redirect()->route('login');

        $ids = $request->input('ids');

        if (!empty($ids)) {
            $items = Item::whereIn('id', $ids)->get();
            $count = 0;

            foreach ($items as $item) {
                if ($item->foto_barang && File::exists(public_path($item->foto_barang))) {
                    File::delete(public_path($item->foto_barang));
                }
                $item->delete();
                $count++;
            }
            return redirect()->route('items.index')->with('success', $count . ' barang berhasil dihapus.');
        }
        return redirect()->route('items.index')->with('error', 'Tidak ada barang yang dipilih untuk dihapus.');
    }

    public function export()
    {
        return Excel::download(new \App\Exports\ItemsExport, 'daftar_inventaris_'.date('Ymd').'.xlsx');
    }

    public function importForm(Request $request)
    {
        if ($this->enforceStrictQrMode($request)) return redirect()->route('login');
        return view('items.import');
    }

    public function import(Request $request) 
    {
        if ($this->enforceStrictQrMode($request)) return redirect()->route('login');

        $request->validate(['file' => 'required|mimes:xlsx,xls,csv,txt']);

        // Tingkatkan memori untuk memproses file excel yang besar (mencegah memory size exhausted)
        ini_set('memory_limit', '1024M');
        // Hilangkan batas waktu eksekusi agar script tidak terhenti di tengah jalan (mencegah timeout 30s)
        set_time_limit(0);

        try {
            Excel::import(new ItemsImport, $request->file('file'));
            return redirect()->route('items.index')->with('success', 'Data barang berhasil diimpor!');
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Gagal import: ' . $e->getMessage()]);
        }
    }
}
