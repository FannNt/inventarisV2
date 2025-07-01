<?php

namespace App\Filament\Resources\CalibrationRelationManagerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CalibrationRelationManager extends RelationManager
{
    protected static string $relationship = 'configure';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('lab_name')
                    ->required(),
                Forms\Components\DatePicker::make('expired_at')
                    ->required()
                    ->label('Masa Berlaku'),
                ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('CalibrationRelation')
            ->columns([
                Tables\Columns\TextColumn::make('lab_name'),
                Tables\Columns\TextColumn::make('calibrate_at')
                    ->date('d M Y')
                    ->label('Tanggal Kalibrasi'),
                Tables\Columns\TextColumn::make('expired_at')
                    ->date('d M Y')
                    ->label('Masa Berlaku')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
