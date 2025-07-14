<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarResource\Pages;
use App\Filament\Resources\CarResource\RelationManagers;
use App\Models\Car;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->directory('cars')
                    ->visibility('private')
                    ->downloadable()
                    ->previewable()
                    ->reorderable()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('nopol')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('nomor_rangka')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('nomor_mesin')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('type')
                    ->required(),
                Forms\Components\TextInput::make('odometer')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('tanggal_pajak')
                    ->required(),
                Forms\Components\Select::make('tahun_pembelian')
                    ->options(
                        collect(range(now()->year, now()->year - 100))
                            ->mapWithKeys(fn($year) => [$year => $year])
                            ->toArray()
                    )->searchable()
                    ->label('Tahun Pembelian')
                    ->required(),
                Forms\Components\Select::make('tahun_perakitan')
                    ->options(
                        collect(range(now()->year, now()->year - 100))
                            ->mapWithKeys(fn($year) => [$year => $year])
                            ->toArray()
                    )->searchable()
                    ->label('Tahun Perakitan')
                    ->required(),
                Forms\Components\TextInput::make('bahan_bakar'),
                Forms\Components\TextInput::make('warna'),
                Forms\Components\TextInput::make('atas_nama')
                    ->required(),
                Forms\Components\Select::make('fungsi')
                    ->options([
                        'ambulance' => 'Ambulance',
                        'pribadi' => 'Pribadi'
                    ]),

            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('nopol'),
                Tables\Columns\TextColumn::make('warna'),
                Tables\Columns\TextColumn::make('fungsi'),
                Tables\Columns\TextColumn::make('tanggal_pajak')
                    ->date('d M Y'),
                Tables\Columns\TextColumn::make('current_service')
                    ->date('d M Y')
                    ->label('Last Service')
            ])
            ->filters([
                //
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
            RelationManagers\CarServiceRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCars::route('/'),
            'create' => Pages\CreateCar::route('/create'),
            'edit' => Pages\EditCar::route('/{record}/edit'),
        ];
    }
}
