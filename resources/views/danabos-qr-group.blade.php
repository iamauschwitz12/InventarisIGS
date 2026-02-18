<!DOCTYPE html>
<html>

<head>
    <title>Print QR Code - Dana BOS</title>
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

    @foreach ($records as $record)
        @php
            $qrUrl = route('danabos.detail', ['id' => $record->id]);
        @endphp

        <div class="page-break">
            <h3>Inventaris Dana BOS</h3>

            <div class="info">
                <div>{{ $record->nama_barang }}</div>
                <div><span class="label">Kode:</span> {{ $record->kode_inventaris }}</div>
            </div>

            <div style="margin-top: 20px;">
                {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($qrUrl) !!}
            </div>
            <br><br>
        </div>
    @endforeach

</body>

</html>