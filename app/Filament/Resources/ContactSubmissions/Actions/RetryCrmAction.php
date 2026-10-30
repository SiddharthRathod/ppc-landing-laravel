<?php

namespace App\Filament\Resources\ContactSubmissions\Actions;

use App\Models\ContactSubmission;
use App\Services\ContactSubmissionService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;

/**
 * "Retry CRM" button, used in the table row and on the view page.
 * Only shown for failed or skipped submissions while the CRM is enabled.
 */
class RetryCrmAction
{
    public static function make(): Action
    {
        return Action::make('retryCrm')
            ->label('Retry CRM')
            ->icon(Heroicon::OutlinedArrowPath)
            ->color('warning')
            ->visible(fn (ContactSubmission $record): bool => $record->crm_status->canRetry() && config('crm.enabled'))
            ->requiresConfirmation()
            ->modalDescription('The submission is queued and sent to the CRM again.')
            ->action(function (ContactSubmission $record, ContactSubmissionService $service): void {
                $service->retryCrm($record);

                Notification::make()->title('Queued for the CRM')->success()->send();
            });
    }
}
