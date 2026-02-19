<?php

namespace App\Observers;

use App\Models\BarangRusak;
use App\Models\Pribadi;
use App\Models\Sekolah;
use App\Models\DanaBos;
use Illuminate\Support\Facades\DB;

class BarangRusakObserver
{
    /**
     * Handle the BarangRusak "created" event.
     * Decrement jumlah on each selected inventory item.
     */
    public function created(BarangRusak $barangRusak): void
    {
        DB::transaction(function () use ($barangRusak) {
            $modelClass = $barangRusak->inventaris_type;
            if (!$modelClass)
                return;

            $ids = is_array($barangRusak->inventaris_id)
                ? $barangRusak->inventaris_id
                : [$barangRusak->inventaris_id];

            foreach ($ids as $itemId) {
                $item = $modelClass::find($itemId);
                if ($item) {
                    // Gunakan GREATEST agar jumlah tidak pernah di bawah 0
                    $modelClass::where('id', $itemId)
                        ->update(['jumlah' => DB::raw('GREATEST(jumlah - 1, 0)')]);
                }
            }
        });
    }
}
