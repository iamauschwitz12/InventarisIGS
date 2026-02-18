<!DOCTYPE html>
<html>

<head>
    <title>Print QR Code</title>
    <style>
        body {
            text-align: center;
            font-family: sans-serif;
            margin-top: 40px;
        }

        img {
            width: 300px;
            margin-top: 20px;
        }

        .info {
            margin-top: 20px;
            font-size: 18px;
            line-height: 28px;
        }

        .label {
            font-weight: bold;
        }

        .page-break {
            page-break-after: always;
            /* or auto/avoid depending on label size */
            margin-bottom: 50px;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 20px;
        }

        @media print {
            .page-break {
                page-break-inside: avoid;
                page-break-after: always;
                border: none;
            }
        }
    </style>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</head>

<body>

    @php
        $start = request('start', 1);
        $jumlah = $record->jumlah;
    @endphp

    @for ($i = 0; $i < $jumlah; $i++)
        @php
            $currentSequence = $start + $i;
            $kodeFull = $record->kode_inventaris . '-' . str_pad($currentSequence, 5, '0', STR_PAD_LEFT);

            // QR Code berisi URL ke halaman detail publik + nomor urut
            // Ini membuat QR code lebih kecil dan mudah di-scan
            $qrUrl = route('pribadi.detail', ['id' => $record->id, 'seq' => $currentSequence]);
        @endphp

        <div class="page-break">
            <h3>Inventaris Pribadi</h3>

            <div class="info">
                <div><span class="label">Nama Barang:</span> {{ $record->nama_barang }}</div>
                <div><span class="label">Kode:</span> {{ $kodeFull }}</div>
            </div>

            <div style="margin-top: 20px;">
                {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($qrUrl) !!}
            </div>
            <br><br>
        </div>
    @endfor

</body>

</html>