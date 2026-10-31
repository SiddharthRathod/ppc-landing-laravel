<?php

namespace App\Filament\Resources\ContactSubmissions\Tables;

use App\Enums\CrmStatus;
use App\Filament\Exports\ContactSubmissionExporter;
use App\Enums\EnquiryStatus;
use App\Filament\Resources\ContactSubmissions\Actions\RetryCrmAction;
use App\Filament\Resources\ContactSubmissions\Actions\UpdateStatusAction;
use App\Models\ContactSubmission;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ContactSubmissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // Shown by default: name, company, email, phone. The rest can be switched on under "Columns".
            ->columns([
                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('company')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('service')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('status')
                    ->badge() // label and colour come from the EnquiryStatus enum
                    ->sortable(),
                TextColumn::make('crm_status')
                    ->label('CRM')
                    ->badge() // label and colour come from the CrmStatus enum
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sent_at')
                    ->label('Sent to CRM')
                    ->dateTime()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', direction: 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(EnquiryStatus::class),
                SelectFilter::make('crm_status')
                    ->label('CRM status')
                    ->options(CrmStatus::class),
                SelectFilter::make('service')
                    ->options(fn () => ContactSubmission::query() // the services that actually appear in submissions
                        ->whereNotNull('service')
                        ->distinct()
                        ->orderBy('service')
                        ->pluck('service', 'service')
                        ->all()),
                TrashedFilter::make(),
            ])
            ->headerActions([
                ExportAction::make()->exporter(ContactSubmissionExporter::class),
            ])
            ->recordActions([
                ViewAction::make(),
                UpdateStatusAction::make(),
                RetryCrmAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()->exporter(ContactSubmissionExporter::class),
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}
