<?php

namespace App\Filament\App\Resources\AreaResource\Pages;

use App\Filament\App\Resources\AreaResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateArea extends CreateRecord
{
    protected static string $resource = AreaResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return parent::getCreatedNotification(); // TODO: Change the autogenerated stub
    }
}
