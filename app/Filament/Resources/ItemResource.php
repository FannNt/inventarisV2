<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CalibrationRelationManagerResource\RelationManagers\CalibrationRelationManager;
use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use App\Models\Ruangan;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()?->hasAnyRole(['admin','superadmin','items_management']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('uuid')
                    ->label('id')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('no_seri')
                    ->label('No.Seri')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Select::make('ruangan_id')
                    ->options(function (){
                    return Ruangan::all()->pluck('name', 'id');
                })
                    ->label('Ruangan')
                    ->required(),
                Forms\Components\Group::make()->relationship(
                    'status',
                    condition: fn(?array $state): bool => filled($state['condition']),
                )->schema([
                    Forms\Components\Select::make('condition')
                        ->options([
                            'baik' => 'Baik',
                            'rusak' => 'Rusak',
                        ])
                        ->required()
                ]),
                Forms\Components\TextInput::make('merk'),
                Forms\Components\TextInput::make('type'),
                Forms\Components\Select::make('tahun_pengadaan')
                    ->options(
                        collect(range(now()->year, now()->year - 30))
                            ->mapWithKeys(fn($year) => [$year => $year])
                            ->toArray()
                    )
                    ->label('Tahun Pengadaan')
                    ->required(),
                Forms\Components\DatePicker::make('expired_at')
                    ->label('Masa Berlaku')
                    ->required()
                    ->visible(fn(string $context) => $context === 'create')
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('ruangan.name')
                    ->label('Ruangan'),
                Tables\Columns\TextColumn::make('status.condition')
                    ->label('Kondisi'),
                Tables\Columns\TextColumn::make('current_expired')
                    ->label('Masa Berlaku')
                    ->date('d M Y')
                    ->color(function (Item $record): string {
                        if (!$record->current_expired) {
                            return 'gray';
                        }

                        $today = now();
                        $threeMonthsFromNow = now()->addMonths(3);
                        $expiryDate = Carbon::parse($record->current_expired);

                        if ($expiryDate->lt($today)) {
                            return 'danger';
                        } elseif ($expiryDate->lt($threeMonthsFromNow)) {
                            return 'warning';
                        } else {
                            return 'success';
                        }
                    }),


            ])
            ->filters([
                Tables\Filters\SelectFilter::make('ruangan_id')
                    ->label('Ruangan')
                    ->options(function (){
                        return Ruangan::all()->pluck('name', 'id');
                    }),
                Tables\Filters\SelectFilter::make('expiration_status')
                    ->label('Expiration Status')
                    ->options([
                        'expired' => 'Expired',
                        'expiring_soon' => 'Expiring Soon',
                        'valid' => 'Valid',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $status = $data['value'];

                        return match ($status) {
                            'expired' => $query->where(function ($q) {
                                $q->whereHas('latestCalibration', function ($sub) {
                                    $sub->whereNotNull('expired_at')
                                        ->where('expired_at', '<', now());
                                })->orWhere(function ($sub) {
                                    $sub->whereNull('expired_at')->whereDoesntHave('latestCalibration');
                                })->orWhere(function ($sub) {
                                    $sub->where('expired_at', '<', now())->whereDoesntHave('latestCalibration');
                                });
                            }),
                            'expiring_soon' => $query->where(function ($q) {
                                $q->whereHas('latestCalibration', function ($sub) {
                                    $sub->whereBetween('expired_at', [now(), now()->addMonths(3)]);
                                })->orWhere(function ($sub) {
                                    $sub->whereBetween('expired_at', [now(), now()->addMonths(3)])->whereDoesntHave('latestCalibration');
                                });
                            }),
                            'valid' => $query->where(function ($q) {
                                $q->whereHas('latestCalibration', function ($sub) {
                                    $sub->where('expired_at', '>', now()->addMonths(3));
                                })->orWhere(function ($sub) {
                                    $sub->where(function ($inner) {
                                        $inner->whereNull('expired_at')
                                            ->orWhere('expired_at', '>', now()->addMonths(3));
                                    })->whereDoesntHave('latestCalibration');
                                });
                            }),
                            default => $query,
                        };
                    }),
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
