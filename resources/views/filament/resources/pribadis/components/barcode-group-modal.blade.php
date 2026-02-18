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
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1rem;
        background: #f9fafb;
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
        background: #dbeafe;
        color: #1d4ed8;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 9999px;
    }

    .barcode-footer {
        display: flex;
        justify-content: center;
        padding: 1rem;
        border-top: 1px solid #e5e7eb;
        margin-top: 0.5rem;
    }

    .barcode-print-btn {
        display: inline-flex;
        align-items: center;
        padding: 0.6rem 1.5rem;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 0.5rem;
        text-decoration: none;
        transition: background 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);
    }

    .barcode-print-btn:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        box-shadow: 0 4px 8px rgba(37, 99, 235, 0.4);
        color: #fff;
    }

    .barcode-print-btn svg {
        width: 1rem;
        height: 1rem;
        margin-right: 0.5rem;
    }
</style>

<div>
    <div class="barcode-header">
        <span class="barcode-badge">
            {{ $records->count() }} Barcode
        </span>
    </div>

    <div class="barcode-grid">
        @foreach ($records as $record)
            @php
                $qrUrl = route('pribadi.detail', ['id' => $record->id]);
            @endphp
            <div class="barcode-card">
                <div class="barcode-qr">
                    {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($qrUrl) !!}
                </div>
                <div class="barcode-kode">{{ $record->kode_inventaris }}</div>
                <div class="barcode-nama">{{ $record->nama_barang }}</div>
            </div>
        @endforeach
    </div>

    @if ($groupId)
        <div class="barcode-footer">
            <a href="{{ route('pribadi.printqr.group', ['groupId' => $groupId]) }}" target="_blank"
                class="barcode-print-btn">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                Print Semua Barcode
            </a>
        </div>
    @endif
</div>