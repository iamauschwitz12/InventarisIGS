<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Inventaris Sekolah - {{ $record->nama_barang }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #059669 0%, #0d9488 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>

<body class="p-4 md:p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-t-2xl shadow-xl p-6 md:p-8">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 md:w-10 md:h-10 text-emerald-600 mr-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Inventaris Sekolah</h1>
                    <p class="text-sm text-gray-500">Detail Barang Inventaris</p>
                </div>
            </div>

            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white p-4 md:p-6 rounded-xl mt-4">
                <h2 class="text-xl md:text-2xl font-bold mb-2">{{ $record->nama_barang }}</h2>
                <p class="text-emerald-100 text-sm md:text-base flex items-center">
                    <svg class="w-4 h-4 md:w-5 md:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                    </svg>
                    {{ $kodeInventarisFull ?? $record->kode_inventaris }}
                </p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white shadow-xl">
            <!-- Informasi Umum -->
            <div class="p-6 md:p-8 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informasi Umum
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 mb-1">No Invoice</dt>
                        <dd class="text-base md:text-lg font-semibold text-gray-900">{{ $record->no_invoice ?? '-' }}
                        </dd>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 mb-1">Nomor Seri</dt>
                        <dd class="text-base md:text-lg font-semibold text-gray-900">{{ $record->no_seri ?? '-' }}</dd>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 mb-1">Jenjang</dt>
                        <dd class="text-base md:text-lg font-semibold text-gray-900">{{ $record->jenjang ?? '-' }}</dd>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 mb-1">Jumlah</dt>
                        <dd class="text-base md:text-lg font-bold text-emerald-600">{{ $record->jumlah ?? '-' }}</dd>
                    </div>
                </div>
            </div>

            <!-- Informasi Finansial -->
            <div class="p-6 md:p-8 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    Informasi Finansial
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-green-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 mb-1">Harga</dt>
                        <dd class="text-xl md:text-2xl font-bold text-green-600">
                            {{ $record->harga ? 'Rp ' . number_format($record->harga, 0, ',', '.') : '-' }}
                        </dd>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 mb-1">Tanggal Beli</dt>
                        <dd class="text-base md:text-lg font-semibold text-gray-900">
                            {{ $record->tgl_beli ? \Carbon\Carbon::parse($record->tgl_beli)->format('d F Y') : '-' }}
                        </dd>
                    </div>
                </div>
            </div>

            <!-- Lokasi -->
            <div class="p-6 md:p-8 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Lokasi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 mb-1">Gedung</dt>
                        <dd class="text-base md:text-lg font-semibold text-gray-900">
                            {{ $record->gedung->nama_gedung ?? '-' }}
                        </dd>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 mb-1">Lantai</dt>
                        <dd class="text-base md:text-lg font-semibold text-gray-900">
                            {{ $record->lantai->lantai ?? '-' }}
                        </dd>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 mb-1">Ruang</dt>
                        <dd class="text-base md:text-lg font-semibold text-gray-900">{{ $record->ruang->ruang ?? '-' }}
                        </dd>
                    </div>
                </div>
            </div>

            <!-- Kategori Aset -->
            <div class="p-6 md:p-8 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                        </path>
                    </svg>
                    Kategori Aset
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-indigo-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 mb-1">Tipe Aset</dt>
                        <dd class="text-base md:text-lg font-semibold text-gray-900">
                            {{ $record->tipeAset->tipe_aset ?? '-' }}
                        </dd>
                    </div>
                    <div class="bg-indigo-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-gray-500 mb-1">Kategori</dt>
                        <dd class="text-base md:text-lg font-semibold text-gray-900">
                            {{ $record->tipeAsetKategori->nama_tipe_kategori ?? '-' }}
                        </dd>
                    </div>
                </div>
            </div>

            <!-- Keterangan -->
            @if ($record->keterangan)
                <div class="p-6 md:p-8 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Keterangan
                    </h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700 leading-relaxed">{{ $record->keterangan }}</p>
                    </div>
                </div>
            @endif

            <!-- Images -->
            @if ($record->img)
                <div class="p-6 md:p-8 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Gambar
                    </h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-center">
                            <img src="{{ asset('storage/' . $record->img) }}" alt="{{ $record->nama_barang }}"
                                class="max-w-full h-auto rounded-lg shadow-lg max-h-64 object-cover">
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="bg-gray-100 rounded-b-2xl shadow-xl p-4 md:p-6">
            <div
                class="flex flex-col md:flex-row justify-between items-center text-xs text-gray-500 space-y-2 md:space-y-0">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Dibuat: {{ $record->created_at ? $record->created_at->format('d M Y H:i') : '-' }}
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Diperbarui: {{ $record->updated_at ? $record->updated_at->format('d M Y H:i') : '-' }}
                </div>
            </div>
            <div class="text-center mt-4 text-sm text-gray-600">
                <p>Sistem Informasi Inventaris Sekolah</p>
            </div>
        </div>
    </div>
</body>

</html>