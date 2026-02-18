<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400"
            style="width: 100%; border-collapse: collapse;">
            <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-white/5 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 font-medium tracking-wider"
                        style="padding: 12px 16px; text-align: left;">Tanggal Cetak</th>
                    <th scope="col" class="px-6 py-3 font-medium tracking-wider"
                        style="padding: 12px 16px; text-align: left;">Kode Awal</th>
                    <th scope="col" class="px-6 py-3 font-medium tracking-wider"
                        style="padding: 12px 16px; text-align: left;">Kode Akhir</th>
                    <th scope="col" class="px-6 py-3 font-medium tracking-wider"
                        style="padding: 12px 16px; text-align: left;">Nama Barang</th>
                    <th scope="col" class="px-6 py-3 font-medium tracking-wider"
                        style="padding: 12px 16px; text-align: left;">Lantai</th>
                    <th scope="col" class="px-6 py-3 font-medium tracking-wider"
                        style="padding: 12px 16px; text-align: left;">Ruang</th>
                    <th scope="col" class="px-6 py-3 font-medium tracking-wider"
                        style="padding: 12px 16px; text-align: left;">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                @forelse($records as $record)
                    @php
                        $start = $record->start_number ?? 1;
                        $jumlah = $record->jumlah ?? 1;
                        $end = $start + $jumlah - 1;

                        $kodeAwal = $record->sekolah->kode_inventaris . '-' . str_pad($start, 5, '0', STR_PAD_LEFT);
                        $kodeAkhir = $record->sekolah->kode_inventaris . '-' . str_pad($end, 5, '0', STR_PAD_LEFT);
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                        <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-900 dark:text-white"
                            style="padding: 12px 16px;">
                            {{ $record->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4" style="padding: 12px 16px;">
                            {{ $kodeAwal }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4" style="padding: 12px 16px;">
                            {{ $kodeAkhir }}
                        </td>
                        <td class="px-6 py-4" style="padding: 12px 16px;">
                            {{ $record->sekolah->nama_barang ?? '-' }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4" style="padding: 12px 16px;">
                            {{ $record->sekolah->lantai->lantai ?? '-' }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4" style="padding: 12px 16px;">
                            {{ $record->sekolah->ruang->ruang ?? '-' }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4" style="padding: 12px 16px;">
                            <button type="button" wire:click="deletePrintHistory({{ $record->id }})"
                                wire:confirm="Yakin ingin menghapus riwayat ini?"
                                class="text-red-600 hover:text-red-900 font-medium">
                                Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400"
                            style="padding: 48px; text-align: center;">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 mb-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    style="width: 48px; height: 48px; display: block; margin: 0 auto 12px;">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                <span class="text-lg font-medium"
                                    style="display: block; font-size: 1.125rem; font-weight: 500;">Belum ada riwayat
                                    cetak</span>
                                <span class="text-sm" style="display: block; font-size: 0.875rem;">Silakan cetak QR code
                                    terlebih dahulu.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>