@if ($getRecord() && $getRecord()->qrcode)
    <img src="{{ asset('storage/' . $getRecord()->qrcode) }}" width="150">
@else
    <p class="text-gray-500 text-sm italic">
        QR Code akan muncul setelah data disimpan.
    </p>
@endif
