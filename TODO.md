# Fixed Group Print Bug in DanaBosTable ✅

## Steps Completed:
- [x] 1. Create print-group-modal.blade.php with QR list and print button (based on danabos-qr-group.blade.php + JS window.print()).
- [x] 2. Update print Action in app/Filament/Resources/DanaBos/Tables/DanaBosTable.php:
     - modalContent: fetch records where group_id + location + jumlah>0 (copy from lihat_barcode_group).
     - Use new view 'filament.resources.danabos.components.print-group-modal'.
     - Preserve PrintHistory, handle single/group.
     - Location-specific filtering.
- [x] 3. Clear caches: php artisan filament:cache-components && php artisan view:clear && php artisan optimize:clear && config:clear.

## Next: Test the fix
1. Go to DanaBos resource list.
2. Find a row with group_id '130' (or any group with multiple locations).
3. Click "Print QR" button.
4. Verify modal shows only items from same location (gedung/lantai/ruang).
5. Click Print button in modal, verify prints correctly.
6. Check PrintHistory created.

If issues, provide screenshot/error or DB query result.

