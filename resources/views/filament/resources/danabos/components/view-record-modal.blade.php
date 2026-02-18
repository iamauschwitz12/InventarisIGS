<div class="space-y-6">
    <!-- Header Info -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold">{{ $record->nama_barang }}</h2>
        <p class="text-blue-100 text-sm mt-1">{{ $record->kode_inventaris ?? '-' }}</p>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Left Column -->
        <div class="space-y-4">
            <!-- Informasi Umum -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-3 uppercase tracking-wider">
                    Informasi Umum</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">No Invoice</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $record->no_invoice ?? '-' }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Nomor Seri</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $record->no_seri ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Jenjang</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $record->jenjang ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Jumlah</dt>
                        <dd class="text-sm font-bold text-blue-600">{{ $record->jumlah ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Informasi Finansial -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-3 uppercase tracking-wider">
                    Informasi Finansial</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Harga</dt>
                        <dd class="text-sm font-bold text-green-600">
                            {{ $record->harga ? 'Rp ' . number_format($record->harga, 0, ',', '.') : '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Tanggal Beli</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $record->tgl_beli ? \Carbon\Carbon::parse($record->tgl_beli)->format('d F Y') : '-' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-4">
            <!-- Lokasi -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-3 uppercase tracking-wider">Lokasi
                </h3>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Gedung</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $record->gedung->nama_gedung ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Lantai</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $record->lantai->lantai ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Ruang</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $record->ruang->ruang ?? '-' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Tipe Aset -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-3 uppercase tracking-wider">Tipe
                    Aset</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">Tipe Aset Dana BOS</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $record->tipeAsetDanaBos->tipe_aset_dana_bos ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Keterangan (Full Width) -->
    @if($record->keterangan)
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2 uppercase tracking-wider">Keterangan</h3>
            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $record->keterangan }}</p>
        </div>
    @endif

    <!-- Image -->
    @if($record->img)
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <h3 class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-2 uppercase tracking-wider">Gambar</h3>
            <div class="flex justify-center">
                <img src="{{ asset('storage/' . $record->img) }}" alt="{{ $record->nama_barang }}"
                    class="max-w-full h-auto rounded-lg shadow max-h-48 object-cover">
            </div>
        </div>
    @endif

    <!-- Metadata -->
    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 text-xs text-gray-500 dark:text-gray-400">
        <div class="flex justify-between items-center">
            <span>Dibuat: {{ $record->created_at ? $record->created_at->format('d F Y H:i') : '-' }}</span>
            <span>Diperbarui: {{ $record->updated_at ? $record->updated_at->format('d F Y H:i') : '-' }}</span>
        </div>
    </div>
</div>