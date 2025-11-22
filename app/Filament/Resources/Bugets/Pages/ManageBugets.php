<?php

namespace App\Filament\Resources\Bugets\Pages;

use App\Filament\Resources\Bugets\BugetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageBugets extends ManageRecords
{
    protected static string $resource = BugetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
