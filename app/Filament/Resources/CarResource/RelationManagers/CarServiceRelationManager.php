<?php

namespace App\Filament\Resources\CarResource\RelationManagers;

use App\Filament\Resources\CarResource;
use App\Models\CarService;
use App\Models\ServiceItem;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CarServiceRelationManager extends RelationManager
{
    protected static string $relationship = 'service';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Select::make('kategori_id')
                    ->relationship('kategori','name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->label('New Kategori')
                            ->unique('car_services_categories','name')
                    ])
                    ->required(),
                Forms\Components\TextInput::make('bengkel'),
                Forms\Components\TextInput::make('keterangan'),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->directory('nota')
                    ->visibility('private')
                    ->downloadable()
                    ->previewable()
                    ->reorderable()
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('service_at')
                    ->required()
                    ->label('Tanggal Service'),
                Repeater::make('reportServiceItems')
                    ->relationship()
                    ->schema([
                        Select::make('service_item_id')
                            ->label('Service Type')
                            ->relationship('serviceItem', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Service Name')
                                    ->required()
                                    ->unique('service_items','name')
                                    ->maxLength(255)
                                    ->placeholder('e.g., Oil Change, Brake Pad Replacement'),
                            ])
                            ->createOptionUsing(function (array $data) {
                                return \App\Models\ServiceItem::create($data);
                            }),

                        TextInput::make('price')
                            ->label('Price for this service')
                            ->required()
                            ->numeric()
                            ->step(1000)
                            ->prefix('Rp')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                self::updateTotal($set, $get);
                            }),
                    ])
                    ->columns(3)
                    ->defaultItems(1)
                    ->addActionLabel('Add Service Item')
                    ->reorderable()
                    ->collapsible()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        self::updateTotal($set, $get);
                    })
                    ->deleteAction(
                        fn ($action) => $action->after(fn (callable $set, callable $get) => self::updateTotal($set, $get))
                    ),

                TextInput::make('total')
                    ->label('Total Service Cost')
                    ->prefix('Rp')
                    ->disabled()
                    ->dehydrated()
                    ->reactive(),

        ])->columns(1);
    }

    protected static function updateTotal(callable $set, callable $get): void
    {
        $items = $get('reportServiceItems') ?? [];
        $total = 0;

        foreach ($items as $item) {
            $total += (float) ($item['price'] ?? 0);
        }

        $set('total', $total);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kategori.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('bengkel')
                    ->searchable()
                    ->default('DIY')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('service_at')
                    ->date('d M Y')
                    ->sortable()
            ])
            ->filters([
                Filter::make('total_range')
                    ->form([
                        TextInput::make('total_from')
                            ->label('Total From')
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('total_to')
                            ->label('Total To')
                            ->numeric()
                            ->prefix('Rp'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['total_from'],
                                fn(Builder $query, $amount): Builder => $query->where('total', '>=', $amount),
                            )
                            ->when(
                                $data['total_to'],
                                fn(Builder $query, $amount): Builder => $query->where('total', '<=', $amount),
                            );
                    })

            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make()
                    ->modal()
                    ->modalWidth('7xl')
                    ->infolist([
                        // Service Overview
                        Split::make([
                            Grid::make(2)
                                ->schema([
                                    Section::make('Service Information')
                                        ->schema([
                                            TextEntry::make('service_at')
                                                ->label('Service Date')
                                                ->date('d M Y')
                                                ->icon('heroicon-o-calendar'),
                                            TextEntry::make('car.nopol')
                                                ->label('Car')
                                                ->formatStateUsing(fn ($record) => $record->car->name . ' ' . $record->car->type . ' - ' . $record->car->nopol)
                                                ->icon('heroicon-o-truck'),
                                            TextEntry::make('kategori')
                                                ->label('Category')
                                                ->badge()
                                                ->color('primary')
                                                ->icon('heroicon-o-tag'),
                                            TextEntry::make('bengkel')
                                                ->label('Service Location')
                                                ->icon('heroicon-o-map-pin'),
                                        ])
                                        ->columnSpan(1),

                                    Section::make('Cost Summary')
                                        ->schema([
                                            TextEntry::make('total')
                                                ->label('Total Cost')
                                                ->money('IDR')
                                                ->weight('bold')
                                                ->color('success')
                                                ->size('lg'),
                                        ])
                                        ->columnSpan(1),
                                ])
                        ]),

                        // Service Items
                        Section::make('Service Items')
                            ->schema([
                                RepeatableEntry::make('reportServiceItems')
                                    ->schema([
                                        TextEntry::make('serviceItem.name')
                                            ->label('Item')
                                            ->weight('bold')
                                            ->icon('heroicon-o-wrench-screwdriver'),
                                        TextEntry::make('quantity')
                                            ->label('Qty')
                                            ->alignCenter(),
                                        TextEntry::make('price')
                                            ->label('Unit Price')
                                            ->money('IDR')
                                            ->alignEnd(),
                                    ])
                                    ->columns(3)
                                    ->grid(3),
                            ])
                            ->collapsible(),

                        // Images and Notes
                        Split::make([
                            Grid::make(2)
                                ->schema([
                                    Section::make('Price Proof & Documentation')
                                        ->schema([
                                            ImageEntry::make('image')
                                                ->label('')
                                                ->columnSpanFull()
                                                ->limit(8)
                                                ->square()
                                                ->stacked()
                                                ->overlap(2)
                                                ->ring(2)
                                                ->size(150),
                                        ])
                                        ->columnSpan(1),

                                    Section::make('Service Notes')
                                        ->schema([
                                            TextEntry::make('keterangan')
                                                ->label('Keterangan')
                                                ->color('warning')
                                                ->icon('heroicon-o-wrench-screwdriver')
                                                ->columnSpanFull(),
                                        ])
                                        ->columnSpan(1),
                                ])
                        ]),

                        // Status
                        Section::make('Status & Actions')
                            ->schema([
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'completed' => 'success',
                                        'in_progress' => 'warning',
                                        'pending' => 'info',
                                        'cancelled' => 'danger',
                                        default => 'gray',
                                    }),
                                TextEntry::make('payment_status')
                                    ->label('Payment')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'paid' => 'success',
                                        'pending' => 'warning',
                                        'overdue' => 'danger',
                                        default => 'gray',
                                    }),
                            ])
                            ->columns(2),
                    ]),
                Tables\Actions\DeleteAction::make(),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

}
