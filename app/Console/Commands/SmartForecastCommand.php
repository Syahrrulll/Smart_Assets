<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item;
use Illuminate\Support\Facades\Cache;

class SmartForecastCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:smart-forecast';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run background heuristic jobs for Predictive Maintenance and Forecasting';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Smart Forecast Analysis...');

        // 1. Predictive Maintenance Heuristic
        // Mark items older than their useful life (umur_ekonomis) or in 'Rusak Ringan' as needing maintenance
        $items = Item::all();
        $maintenance_candidates = 0;
        
        foreach ($items as $item) {
            $age = date('Y') - ($item->tahun_barang ?? date('Y'));
            if ($age >= ($item->umur_ekonomis ?? 5) || $item->kondisi_barang === 'Rusak Ringan') {
                $maintenance_candidates++;
            }
        }

        Cache::put('predictive_maintenance_count', $maintenance_candidates, 86400); // cache for 1 day
        $this->info("Found {$maintenance_candidates} items needing predictive maintenance/replacement.");

        // We already compute Smart Insights dynamically with Cache, but we can pre-warm it here.
        $this->call('cache:clear');
        $this->info('Cache cleared to force insight regeneration on next dashboard load.');

        $this->info('Smart Forecast Analysis Completed.');
    }
}
