<div class="space-y-2">
    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
        Berikut adalah daftar semua kolom yang tersedia di tabel Pribadis.
        Kolom yang bertanda "Tersembunyi secara default" dapat ditampilkan dengan menggunakan tombol toggle kolom di
        pojok kanan atas tabel.
    </p>

    <div class="rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($columns as $index => $column)
                <div class="px-4 py-3 {{ $index % 2 === 0 ? 'bg-gray-50 dark:bg-gray-800' : 'bg-white dark:bg-gray-900' }}">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $column }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
        <div class="flex items-start">
            <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                    clip-rule="evenodd"></path>
            </svg>
            <div class="flex-1">
                <p class="text-sm text-blue-800 dark:text-blue-200">
                    <strong>Tips:</strong> Gunakan menu "Toggle Columns" (ikon kolom) di pojok kanan atas tabel untuk
                    menampilkan atau menyembunyikan kolom tertentu.
                </p>
            </div>
        </div>
    </div>
</div>