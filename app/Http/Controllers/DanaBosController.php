<?php

namespace App\Http\Controllers;

use App\Models\DanaBos;
use Illuminate\Http\Request;

class DanaBosController extends Controller
{
    /**
     * Display detailed information for a specific Dana BOS inventory item
     * This is a public page accessible via QR code scanning
     */
    public function showQrDetail($id, Request $request)
    {
        // Load the record with all relationships
        $record = DanaBos::with([
            'gedung',
            'lantai',
            'ruang',
            'tipeAsetDanaBos',
            'tipeAset',
            'tipeAsetKategori'
        ])->findOrFail($id);

        // Get sequence number from query parameter (if provided)
        $sequenceNumber = $request->query('seq');

        // Generate full inventory code with sequence
        $kodeInventarisFull = $record->kode_inventaris;
        if ($sequenceNumber) {
            $kodeInventarisFull .= '-' . str_pad($sequenceNumber, 5, '0', STR_PAD_LEFT);
        }

        // Hitung jumlah total dari grup (atau fallback ke jumlah record untuk data lama)
        $jumlahTotal = $record->group_id
            ? DanaBos::where('group_id', $record->group_id)->count()
            : $record->jumlah;

        return view('danabos-detail', compact('record', 'kodeInventarisFull', 'jumlahTotal'));
    }
}
