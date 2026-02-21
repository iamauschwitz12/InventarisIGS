<?php

namespace App\Observers;

use App\Models\TransferAntarRuang;
use App\Models\Pribadi;
use App\Models\Sekolah;
use App\Models\DanaBos;
use Illuminate\Support\Facades\DB;

class TransferAntarRuangObserver
{
    /**
     * Handle the TransferAntarRuang "creating" event.
     * Populate kode_inventaris from selected items so the column is never empty.
     */
    public function creating(TransferAntarRuang $transfer): void
    {
        $modelClass = match ($transfer->jenis_inventaris) {
            'pribadi' => Pribadi::class,
            'sekolah' => Sekolah::class,
            'dana_bos' => DanaBos::class,
            default => null,
        };

        if ($modelClass && empty($transfer->kode_inventaris)) {
            $sumberIds = is_array($transfer->sumber_id) ? $transfer->sumber_id : [$transfer->sumber_id];
            $kodes = $modelClass::whereIn('id', $sumberIds)->pluck('kode_inventaris')->filter()->values()->toArray();
            $transfer->kode_inventaris = $kodes;
        }
    }

    /**
     * Handle the TransferAntarRuang "created" event.
     * Move inventory items from source lantai/ruang to destination lantai/ruang (same gedung).
     */
    public function created(TransferAntarRuang $transfer): void
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

                // Update source: move item to destination lantai/ruang (same gedung)
                $sourceItem->update([
                    'lantai_id' => $transfer->lantai_tujuan_id,
                    'ruang_id' => $transfer->ruang_tujuan_id,
                ]);
            }
        });
    }
}
