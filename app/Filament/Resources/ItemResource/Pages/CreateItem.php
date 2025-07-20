<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use App\Models\ItemInventaris;
use App\Models\ItemStatus;
use Filament\Resources\Pages\CreateRecord;

class CreateItem extends CreateRecord
{
    protected static string $resource = ItemResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $status = ItemStatus::create([
            'condition' => $data['condition']
        ]);
        $data['items_status_id'] = $status->id;

        unset($data['condition']);

        return $data;
    }
}
