<?php

namespace App\Filament\Resources\AcceptAdminResource\Pages;

use App\Filament\Resources\AcceptAdminResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAcceptAdmin extends EditRecord
{
    protected static string $resource = AcceptAdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
