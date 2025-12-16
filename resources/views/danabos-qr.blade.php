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
    </style>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</head>
<body>

    <h3>Inventaris Dana Bos</h3>

    <div class="info">
        <div><span class="label">Nama Barang:</span> {{ $record->nama_barang }}</div>
        <div><span class="label">Ruang:</span> {{ $record->ruang->ruang ?? '-' }}</div>
        <div><span class="label">Lantai:</span> {{ $record->lantai->lantai ?? '-' }}</div>
    </div>

    <img src="{{ asset('storage/' . $record->qrcode) }}" alt="QR Code Dana Bos">

</body>
</html>

