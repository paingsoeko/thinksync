<?php

namespace App\Filament\App\Resources\AreaResource\Pages;

use App\Filament\App\Resources\AreaResource;
use App\Models\Area;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArea extends EditRecord
{
    protected static string $resource = AreaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function calculateCompletionPercentage(Area $area)
    {
        $totalProjects = $area->projects()->count();
        $completedProjects = $area->projects()->where('status', 'completed')->count();

        if ($totalProjects > 0) {
            $completionPercentage = ($completedProjects / $totalProjects) * 100;
        } else {
            $completionPercentage = 0;
        }

        return $completionPercentage;
    }
}
