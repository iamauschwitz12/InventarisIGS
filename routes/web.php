<?php

use Illuminate\Support\Facades\Route;
use App\Models\Pribadi;
use App\Models\Sekolah;
use App\Models\DanaBos;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/print-qr/{record}', function (Pribadi $record) {
    return view('print-qr', compact('record'));
})->name('pribadi.printqr');

Route::get('/sekolah-qr/{record}', function ($id) {
    // Mencari data berdasarkan ID dan memuat relasinya agar tidak error di blade
    $record = Sekolah::with(['ruang', 'lantai'])->findOrFail($id);

    return view('sekolah-qr', [
        'record' => $record
    ]);
})->name('sekolah.sekolahqr');

Route::get('/danabos-qr/{record}', function (DanaBos $record) {
    return view('danabos-qr', compact('record'));
})->name('danabos.danabosqr');