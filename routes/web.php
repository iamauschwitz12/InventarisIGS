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

Route::get('/sekolah-qr/{record}', function (Sekolah $record) {
    return view('sekolah-qr', compact('record'));
})->name('sekolah.sekolahqr');

Route::get('/danabos-qr/{record}', function (DanaBos $record) {
    return view('danabos-qr', compact('record'));
})->name('danabos.danabosqr');