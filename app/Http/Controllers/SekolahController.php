<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;

class SekolahController extends Controller
{
    /**
     * Display detailed information for a specific school inventory item
     * This is a public page accessible via QR code scanning
     */
    public function showQrDetail($id, Request $request)
    {
        // Load the record with all relationships
        $record = Sekolah::with([
            'gedung',
            'lantai',
            'ruang',
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

        return view('sekolah-detail', compact('record', 'kodeInventarisFull'));
    }
}
