<?php

namespace App\Filament\Resources\ContactSubmissions\Schemas;

use App\Models\ContactSubmission;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactSubmissionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Lead')
                    ->columns(2)
                    ->components([
                        TextEntry::make('name'),
                        TextEntry::make('company')->placeholder('-'),
                        TextEntry::make('email')->copyable(),
                        TextEntry::make('phone')->copyable(),
                        TextEntry::make('service')->placeholder('-'),
                        TextEntry::make('created_at')->label('Received')->dateTime(),
                        TextEntry::make('message')->columnSpanFull(),
                    ]),
                Section::make('CRM')
                    ->columns(2)
                    ->components([
                        TextEntry::make('crm_status')->label('Status')->badge(),
                        TextEntry::make('sent_at')->label('Sent at')->dateTime()->placeholder('-'),
                        TextEntry::make('crm_response')->label('CRM response')->placeholder('-')->columnSpanFull(),
                    ]),
                Section::make('Technical')
                    ->columns(2)
                    ->collapsed()
                    ->components([
                        TextEntry::make('ip_address')->label('IP address')->placeholder('-'),
                        TextEntry::make('deleted_at')
                            ->dateTime()
                            ->visible(fn (ContactSubmission $record): bool => $record->trashed()),
                        TextEntry::make('user_agent')->placeholder('-')->columnSpanFull(),
                    ]),
            ]);
    }
}
