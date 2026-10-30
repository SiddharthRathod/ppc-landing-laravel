<?php

namespace App\Filament\Exports;

use App\Enums\CrmStatus;
use App\Models\ContactSubmission;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

/**
 * CSV / XLSX export of contact submissions. Runs as a queued batch,
 * so `php artisan queue:work` must be running; the download link
 * arrives as a notification (bell icon) in the admin.
 */
class ContactSubmissionExporter extends Exporter
{
    protected static ?string $model = ContactSubmission::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('created_at')
                ->label('Received'),
            ExportColumn::make('name'),
            ExportColumn::make('company'),
            ExportColumn::make('email'),
            ExportColumn::make('phone'),
            ExportColumn::make('service'),
            ExportColumn::make('message'),
            ExportColumn::make('crm_status')
                ->label('CRM status')
                ->formatStateUsing(fn (CrmStatus $state): string => $state->getLabel()),
            ExportColumn::make('sent_at')
                ->label('Sent to CRM'),
            // Technical columns: available, but unticked by default.
            ExportColumn::make('crm_response')
                ->label('CRM response')
                ->enabledByDefault(false),
            ExportColumn::make('ip_address')
                ->label('IP address')
                ->enabledByDefault(false),
            ExportColumn::make('user_agent')
                ->enabledByDefault(false),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your contact submission export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
