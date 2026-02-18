<?php

namespace App\Observers;

use App\Models\TransferInventaris;
use App\Models\Pribadi;
use App\Models\Sekolah;
use App\Models\DanaBos;
use Illuminate\Support\Facades\DB;

class TransferInventarisObserver
{
    /**
     * Handle the TransferInventaris "created" event.
     */
    public function created(TransferInventaris $transfer): void
    {
        DB::transaction(function () use ($transfer) {
            $modelClass = match ($transfer->jenis_inventaris) {
                'pribadi' => Pribadi::class,
                'sekolah' => Sekolah::class,
                'dana_bos' => DanaBos::class,
                default => null,
            };

            if (!$modelClass) {
                return;
            }

            $sumberIds = is_array($transfer->sumber_id) ? $transfer->sumber_id : [$transfer->sumber_id];

            foreach ($sumberIds as $itemId) {
                $sourceItem = $modelClass::find($itemId);
                if (!$sourceItem) {
                    continue;
                }

                // Update source: move item to destination location
                $sourceItem->update([
                    'gedung_id' => $transfer->gedung_tujuan_id,
                    'lantai_id' => $transfer->lantai_tujuan_id,
                    'ruang_id' => $transfer->ruang_tujuan_id,
                ]);
            }
        });
    }
}
