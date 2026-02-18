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

// Public detail page for QR code scanning
// Accepts optional ?seq=X parameter for sequence number
Route::get('/inventaris/{id}/detail', [App\Http\Controllers\PribadiController::class, 'showQrDetail'])
    ->name('pribadi.detail');

Route::get('/sekolah-qr/{record}', function (Sekolah $record) {
    return view('sekolah-qr', compact('record'));
})->name('sekolah.sekolahqr');

// Public detail page for Sekolah QR code scanning
Route::get('/inventaris-sekolah/{id}/detail', [App\Http\Controllers\SekolahController::class, 'showQrDetail'])
    ->name('sekolah.detail');

Route::get('/danabos-qr/{record}', function (DanaBos $record) {
    return view('danabos-qr', compact('record'));
})->name('danabos.danabosqr');

// Public detail page for DanaBos QR code scanning
Route::get('/inventaris-danabos/{id}/detail', [App\Http\Controllers\DanaBosController::class, 'showQrDetail'])
    ->name('danabos.detail');

// Print all QR codes in a group
Route::get('/pribadi-qr-group/{groupId}', function (string $groupId) {
    $records = Pribadi::where('group_id', $groupId)->orderBy('kode_inventaris')->get();
    abort_if($records->isEmpty(), 404);
    return view('pribadi-qr-group', compact('records'));
})->name('pribadi.printqr.group');

Route::get('/sekolah-qr-group/{groupId}', function (string $groupId) {
    $records = Sekolah::where('group_id', $groupId)->orderBy('kode_inventaris')->get();
    abort_if($records->isEmpty(), 404);
    return view('sekolah-qr-group', compact('records'));
})->name('sekolah.sekolahqr.group');

Route::get('/danabos-qr-group/{groupId}', function (string $groupId) {
    $records = DanaBos::where('group_id', $groupId)->orderBy('kode_inventaris')->get();
    abort_if($records->isEmpty(), 404);
    return view('danabos-qr-group', compact('records'));
})->name('danabos.danabosqr.group');