
<style>
.barcode-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
  gap: 1rem;
  padding: 1rem;
}
.barcode-card {
  /* all from barcode-group-modal */
}
.barcode-qr {
  /* ... */
}
.barcode-kode {
  /* ... */
}
.barcode-nama {
  /* ... */
}
.barcode-header {
  /* ... */
}
.barcode-badge {
  /* ... */
}
.barcode-footer {
  /* ... */
}
.barcode-print-btn {
  /* ... */
}
.print-section {
  page-break-inside: avoid;
}
@media print {
  .barcode-grid {
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 0.5rem;
  }
  body * {
    visibility: hidden;
  }
  .barcode-grid, .barcode-grid * {
    visibility: visible;
  }
  .barcode-grid {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }
}
</style>

<div>
  <div class="barcode-header">
    <span class="barcode-badge">
      {{ $records->count() }} item{{ $records->count() !== 1 ? 's' : '' }} di grup {{ $groupId ?? 'Single' }}
    </span>
  </div>

  <div class="barcode-grid">
    @foreach ($records as $index => $record)
      @php $qrUrl = route('danabos.detail', ['id' => $record->id]); @endphp
      <div class="barcode-card print-section">
        <div class="barcode-qr">
          {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($qrUrl) !!}
        </div>
        <div class="barcode-kode">{{ $record->kode_inventaris }}</div>
        <div class="barcode-nama">{{ $record->nama_barang }}</div>
        <div style="font-size: 0.6rem; color: #6b7280; text-align: center;">
          {{ $record->ruang?->ruang }}, {{ $record->lantai?->lantai }}
        </div>
      </div>
    @endforeach
  </div>

  <div class="barcode-footer">
    <button onclick="window.print();" class="barcode-print-btn" style="background: #059669; ">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:1rem;height:1rem;margin-right:0.5rem">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
      </svg>
      Print QR Codes
    </button>
    @if($groupId)
      <a href="{{ route('danabos.danabosqr.group', $groupId) }}" target="_blank" class="barcode-print-btn" style="background: #dc2626; margin-left: 0.5rem;">
        Full Print (All Locations)
      </a>
    @endif
  </div>
</div>

<script>
  // Optional: auto focus print button or preview
</script>

