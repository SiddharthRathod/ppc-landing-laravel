<?php

namespace App\Filament\Resources\ContactSubmissions\Actions;

use App\Enums\EnquiryStatus;
use App\Models\ContactSubmission;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;

/**
 * "Update status" button (table row and view page): opens a small form to
 * set the follow-up status and the internal notes of an enquiry.
 */
class UpdateStatusAction
{
    public static function make(): Action
    {
        return Action::make('updateStatus')
            ->label('Update status')
            ->icon(Heroicon::OutlinedPencilSquare)
            ->modalHeading(fn (ContactSubmission $record): string => 'Update enquiry from '.$record->name)
            ->modalSubmitActionLabel('Save')
            ->fillForm(fn (ContactSubmission $record): array => [
                'status' => $record->status,
                'notes' => $record->notes,
            ])
            ->schema([
                Select::make('status')
                    ->options(EnquiryStatus::class)
                    ->required(),
                Textarea::make('notes')
                    ->label('Internal notes')
                    ->rows(4)
                    ->maxLength(5000)
                    ->helperText('Only visible in the admin.'),
            ])
            ->action(function (array $data, ContactSubmission $record): void {
                $record->update($data);

                Notification::make()->title('Enquiry updated')->success()->send();
            });
    }
}
