<?php

namespace App\Filament\Resources\TipeAsetDanaBos;

use App\Filament\Resources\TipeAsetDanaBos\Pages\CreateTipeAsetDanaBos;
use App\Filament\Resources\TipeAsetDanaBos\Pages\EditTipeAsetDanaBos;
use App\Filament\Resources\TipeAsetDanaBos\Pages\ListTipeAsetDanaBos;
use App\Filament\Resources\TipeAsetDanaBos\Schemas\TipeAsetDanaBosForm;
use App\Filament\Resources\TipeAsetDanaBos\Tables\TipeAsetDanaBosTable;
use App\Models\TipeAsetDanaBos;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TipeAsetDanaBosResource extends Resource
{
    protected static ?string $model = TipeAsetDanaBos::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'TipeAsetDanaBos';

    protected static string | UnitEnum | null $navigationGroup = 'Kategori Manajemen';

    public static function form(Schema $schema): Schema
    {
        return TipeAsetDanaBosForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TipeAsetDanaBosTable::configure($table);
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
            'index' => ListTipeAsetDanaBos::route('/'),
            'create' => CreateTipeAsetDanaBos::route('/create'),
            'edit' => EditTipeAsetDanaBos::route('/{record}/edit'),
        ];
    }
}
