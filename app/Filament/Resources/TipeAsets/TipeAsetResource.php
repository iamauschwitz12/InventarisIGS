<?php

namespace App\Filament\Resources\TipeAsets;

use App\Filament\Resources\TipeAsets\Pages\CreateTipeAset;
use App\Filament\Resources\TipeAsets\Pages\EditTipeAset;
use App\Filament\Resources\TipeAsets\Pages\ListTipeAsets;
use App\Filament\Resources\TipeAsets\Schemas\TipeAsetForm;
use App\Filament\Resources\TipeAsets\Tables\TipeAsetsTable;
use App\Models\TipeAset;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TipeAsetResource extends Resource
{
    protected static ?string $model = TipeAset::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static ?string $recordTitleAttribute = 'TipeAset';

    protected static string|UnitEnum|null $navigationGroup = 'Kategori Manajemen';

    public static function form(Schema $schema): Schema
    {
        return TipeAsetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TipeAsetsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTipeAsets::route('/'),
            'create' => CreateTipeAset::route('/create'),
            'edit' => EditTipeAset::route('/{record}/edit'),
        ];
    }
}
