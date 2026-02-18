<style>
    .barcode-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
        padding: 1rem;
    }

    .barcode-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        border: 1px solid #fecaca;
        border-radius: 0.75rem;
        padding: 1rem;
        background: #fef2f2;
        transition: box-shadow 0.2s, transform 0.2s;
    }

    .barcode-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .barcode-qr {
        width: 120px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border-radius: 0.5rem;
        padding: 0.5rem;
        border: 1px solid #e5e7eb;
    }

    .barcode-qr svg {
        width: 100% !important;
        height: 100% !important;
    }

    .barcode-kode {
        margin-top: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: #1f2937;
        text-align: center;
        word-break: break-all;
        line-height: 1.3;
    }

    .barcode-nama {
        font-size: 0.65rem;
        color: #6b7280;
        text-align: center;
        margin-top: 0.15rem;
    }

    .barcode-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0 1rem;
        margin-bottom: 0.25rem;
    }

    .barcode-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 9999px;
    }

    .barcode-badge-rusak {
        background: #fee2e2;
        color: #991b1b;
    }

    .barcode-badge-jenis {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .rusak-info {
        padding: 0.75rem 1rem;
        margin: 0.5rem 1rem;
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 0.5rem;
        font-size: 0.8rem;
        color: #991b1b;
    }

    .rusak-info strong {
        font-weight: 600;
    }

    .rusak-status {
        display: inline-block;
        margin-top: 0.25rem;
        padding: 0.1rem 0.4rem;
        background: #dc2626;
        color: #fff;
        font-size: 0.6rem;
        font-weight: 600;
        border-radius: 0.25rem;
        text-transform: uppercase;
    }
</style>

<div>
    <div class="barcode-header">
        <span class="barcode-badge barcode-badge-rusak">
            {{ $records->count() }} Barang Rusak
        </span>
        <span class="barcode-badge barcode-badge-jenis">
            {{ class_basename($barangRusak->inventaris_type) }}
        </span>
    </div>

    <div class="rusak-info">
        <strong>Tanggal Rusak:</strong> {{ $barangRusak->tgl_rusak->format('d/m/Y') }} |
        <strong>Gedung:</strong> {{ $barangRusak->gedung->nama_gedung ?? '-' }} |
        <strong>Ruang:</strong> {{ $barangRusak->ruang->ruang ?? '-' }}
        @if($barangRusak->keterangan)
            | <strong>Keterangan:</strong> {{ $barangRusak->keterangan }}
        @endif
    </div>

    <div class="barcode-grid">
        @foreach ($records as $record)
            @php
                $qrUrl = match ($jenis) {
                    'pribadi' => route('pribadi.detail', ['id' => $record->id]),
                    'sekolah' => route('sekolah.detail', ['id' => $record->id]),
                    'dana_bos' => route('danabos.detail', ['id' => $record->id]),
                    default => '#',
                };
            @endphp
            <div class="barcode-card">
                <div class="barcode-qr">
                    {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($qrUrl) !!}
                </div>
                <div class="barcode-kode">{{ $record->kode_inventaris }}</div>
                <div class="barcode-nama">{{ $record->nama_barang }}</div>
                <span class="rusak-status">Rusak</span>
            </div>
        @endforeach
    </div>
</div>