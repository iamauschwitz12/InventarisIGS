<div class="space-y-6">
    <!-- Header Info -->
    <div class="bg-gradient-to-r from-primary-500 to-primary-600 text-white p-6 rounded-lg shadow-lg">
        
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Left Column -->
        <div class="space-y-4">
            <!-- Informasi Umum -->

            <!-- Informasi Finansial -->
            
        </div>

        <!-- Right Column -->
        <div class="space-y-4">
            <!-- Lokasi -->
            

            <!-- Kategori Aset -->
            
        </div>
    </div>

    <!-- Keterangan (Full Width) -->

    <!-- Metadata -->
    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 text-xs text-gray-500 dark:text-gray-400">
        <div class="flex justify-between items-center">
            <span>Dibuat: {{ $record->created_at ? $record->created_at->format('d F Y H:i') : '-' }}</span>
            <span>Diperbarui: {{ $record->updated_at ? $record->updated_at->format('d F Y H:i') : '-' }}</span>
        </div>
    </div>
</div>