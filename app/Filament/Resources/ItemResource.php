<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CalibrationRelationManagerResource\RelationManagers\CalibrationRelationManager;
use App\Filament\Resources\ItemResource\Pages;
use App\Models\Item;
use App\Models\ItemInventaris;
use App\Models\ItemStatus;
use App\Models\Ruangan;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends Resource
{
    protected static ?string $model = ItemInventaris::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()?->hasAnyRole(['admin','superadmin','items_management']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('item_id')
                    ->relationship('item','name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('no_seri')
                            ->label('No.Seri')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\Select::make('merk_id')
                            ->relationship('merk','name')
                            ->label('Merk')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->label('New Merk')
                                    ->unique('merks','name')
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('jumlah')
                            ->required()
                            ->numeric(),
                        Forms\Components\Select::make('tahun_pengadaan')
                            ->options(
                                collect(range(now()->year, now()->year - 30))
                                    ->mapWithKeys(fn($year) => [$year => $year])
                                    ->toArray()
                            )
                            ->label('Tahun Pengadaan')
                            ->required(),
                    ])
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('ruangan_id')
                    ->relationship('ruangan', 'name')
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->unique('ruangans','name'),
                    ])
                    ->required()
                    ->searchable(),
                Select::make('condition')
                    ->label('Condition')
                    ->options([
                        'baik' => 'Baik',
                        'rusak' => 'Rusak',
                    ])
                    ->required(),
                Select::make('asal_barang')
                    ->options([
                        'Beli' => 'Beli',
                        'Bantuan' => 'Bantuan',
                        'Hibah' => 'Hibah'
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('tgl_pengadaan')
                    ->required(),
                TextInput::make('harga')
                    ->required()
                    ->numeric(),
                TextInput::make('no_rak')
                    ->required()
                    ->numeric(),
                TextInput::make('no_box')
                    ->required()
                    ->numeric(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ruangan.name'),
                Tables\Columns\TextColumn::make('item.merk.name')
                    ->sortable()
                    ->label('Merk'),
                Tables\Columns\TextColumn::make('tgl_pengadaan')
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CalibrationRelationManager::class
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
