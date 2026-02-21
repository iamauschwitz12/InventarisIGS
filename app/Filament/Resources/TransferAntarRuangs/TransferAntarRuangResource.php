<?php

namespace App\Filament\Resources\TransferAntarRuangs;

use App\Filament\Resources\TransferAntarRuangs\Pages\CreateTransferAntarRuang;
use App\Filament\Resources\TransferAntarRuangs\Pages\EditTransferAntarRuang;
use App\Filament\Resources\TransferAntarRuangs\Pages\ListTransferAntarRuangs;
use App\Filament\Resources\TransferAntarRuangs\Pages\ViewTransferAntarRuang;
use App\Filament\Resources\TransferAntarRuangs\Schemas\TransferAntarRuangForm;
use App\Filament\Resources\TransferAntarRuangs\Schemas\TransferAntarRuangInfolist;
use App\Filament\Resources\TransferAntarRuangs\Tables\TransferAntarRuangsTable;
use App\Models\TransferAntarRuang;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TransferAntarRuangResource extends Resource
{
    protected static ?string $model = TransferAntarRuang::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowPath;

    protected static ?string $recordTitleAttribute = 'TransferAntarRuang';

    protected static ?string $navigationLabel = 'Antar Ruang / Lantai';

    protected static string | UnitEnum | null $navigationGroup = 'Transfer inventaris';

    public static function form(Schema $schema): Schema
    {
        return TransferAntarRuangForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TransferAntarRuangInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransferAntarRuangsTable::configure($table);
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
            'index' => ListTransferAntarRuangs::route('/'),
            'create' => CreateTransferAntarRuang::route('/create'),
            'view' => ViewTransferAntarRuang::route('/{record}'),
            'edit' => EditTransferAntarRuang::route('/{record}/edit'),
        ];
    }
}
