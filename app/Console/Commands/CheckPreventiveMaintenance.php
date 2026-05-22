<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item;
use App\Models\MaintenanceTicket;
use Carbon\Carbon;

class CheckPreventiveMaintenance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asset:check-maintenance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Periksa barang yang jatuh tempo perawatan preventif dan buat tiket jika perlu.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $items = Item::whereNotNull('maintenance_interval_months')->get();
        $count = 0;

        foreach ($items as $item) {
            if (!$item->last_maintenance_date) {
                // Jika belum pernah diservis, anggap sejak dibuat atau dibeli
                $baseDate = $item->created_at;
            } else {
                $baseDate = Carbon::parse($item->last_maintenance_date);
            }

            $dueDate = $baseDate->copy()->addMonths($item->maintenance_interval_months);

            if (Carbon::now()->greaterThanOrEqualTo($dueDate)) {
                // Periksa apakah sudah ada tiket maintenance 'open' untuk item ini
                $existingTicket = MaintenanceTicket::where('item_id', $item->id)
                    ->where('status', 'open')
                    ->first();

                if (!$existingTicket) {
                    MaintenanceTicket::create([
                        'item_id' => $item->id,
                        'reported_by' => 'Sistem (Otomatis)',
                        'issue_description' => 'Perawatan Preventif Berkala (' . $item->maintenance_interval_months . ' Bulan)',
                        'status' => 'open'
                    ]);
                    $count++;
                }
            }
        }

        $this->info("Pemeriksaan selesai. $count tiket maintenance otomatis dibuat.");
    }
}
