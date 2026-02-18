<?php

namespace App\Filament\Resources\TransferInventaris;

use App\Filament\Resources\TransferInventaris\Pages\CreateTransferInventaris;
use App\Filament\Resources\TransferInventaris\Pages\EditTransferInventaris;
use App\Filament\Resources\TransferInventaris\Pages\ListTransferInventaris;
use App\Filament\Resources\TransferInventaris\Schemas\TransferInventarisForm;
use App\Filament\Resources\TransferInventaris\Tables\TransferInventarisTable;
use App\Models\TransferInventaris;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TransferInventarisResource extends Resource
{
    protected static ?string $model = TransferInventaris::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowRightCircle;

    protected static ?string $recordTitleAttribute = 'TransferInventaris';

    protected static string | UnitEnum | null $navigationGroup = 'Menu inventaris';

    public static function form(Schema $schema): Schema
    {
        return TransferInventarisForm::configure($schema);
    }
    public static function table(Table $table): Table
    {
        return TransferInventarisTable::configure($table);
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
            'index' => ListTransferInventaris::route('/'),
            'create' => CreateTransferInventaris::route('/create'),
            'edit' => EditTransferInventaris::route('/{record}/edit'),
        ];
    }
}
