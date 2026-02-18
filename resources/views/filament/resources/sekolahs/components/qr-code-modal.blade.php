<div class="p-4 space-y-4">
    <div class="flex flex-col items-center">
        <h3 class="font-bold text-lg mb-2">Kode Induk</h3>
        <div>
            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate($record->kode_inventaris) !!}
        </div>
        <div class="text-center font-bold text-md mt-2">
            {{ $record->kode_inventaris }}
        </div>
        <div class="text-center text-sm text-gray-500">
            {{ $record->nama_barang }} (Total: {{ $record->jumlah }})
        </div>
    </div>

    <hr class="my-4">

    <div>
        <h3 class="font-bold text-lg mb-4 text-center">Daftar Kode Satuan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-96 overflow-y-auto">
            @for ($i = 1; $i <= min($record->jumlah, 50); $i++)
                @php
                    $fullCode = $record->kode_inventaris . '-' . sprintf('%05d', $i);
                @endphp
                <div class="border rounded p-3 flex flex-col items-center bg-gray-50">
                    <div class="mb-2">
                        {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)->generate($fullCode) !!}
                    </div>
                    <div class="text-xs font-mono font-bold">
                        {{ $fullCode }}
                    </div>
                </div>
            @endfor
            @if ($record->jumlah > 50)
                <div class="col-span-full text-center text-gray-500 italic p-2">
                    Menampilkan 50 dari {{ $record->jumlah }} item. Gunakan fitur Print untuk melihat semua.
                </div>
            @endif
        </div>
    </div>
</div>