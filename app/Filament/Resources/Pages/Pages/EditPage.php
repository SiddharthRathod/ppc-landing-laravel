<?php

namespace App\Filament\Resources\Pages\Pages;

use App\Filament\Resources\Pages\PageResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    // No delete: Home and Contact are needed by the site's routes.
    protected function getHeaderActions(): array
    {
        return [
            Action::make('view')
                ->label('View on site')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->color('gray')
                ->url(fn (): string => $this->getRecord()->url())
                ->openUrlInNewTab(),
        ];
    }
}
