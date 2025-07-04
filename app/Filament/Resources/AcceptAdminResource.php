<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AcceptAdminResource\Pages;
use App\Models\RoleRequest;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AcceptAdminResource extends Resource
{
    protected static ?string $model = RoleRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Username'),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('User Email'),
                Tables\Columns\TextColumn::make('role_request')
                    ->label('Requesting Role'),
                Tables\Columns\TextColumn::make('status')
                    ->label('status')
                    ->badge()
                    ->color(fn($state) => match ($state){
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger'
                    })
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'approved' => 'Approved',
                        'pending' => 'Pending',
                        'rejected' => 'Rejected'
                    ])
            ])
            ->actions([
                Tables\Actions\Action::make('Approve')
                    ->visible(fn($record) => $record->status === 'pending')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (RoleRequest $record) {
                        $record->update(['status' => 'approved']);
                        $record->user->assignRole($record->role_request);
                        Notification::make()
                            ->title('Request Approved')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('Reject')
                    ->visible(fn($record) => $record->status === 'pending')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (RoleRequest $record) {
                        $record->update(['status' => 'rejected']);
                        Notification::make()
                            ->title('Request Rejected')
                            ->danger()
                            ->send();
                    })

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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAcceptAdmins::route('/'),
            'create' => Pages\CreateAcceptAdmin::route('/create'),
            'edit' => Pages\EditAcceptAdmin::route('/{record}/edit'),
        ];
    }
}
