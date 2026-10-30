<?php

namespace App\Filament\Resources\ContactSubmissions\Pages;

use App\Filament\Resources\ContactSubmissions\ContactSubmissionResource;
use Filament\Resources\Pages\ListRecords;

// No "Create" button: submissions only come from the website form.
class ListContactSubmissions extends ListRecords
{
    protected static string $resource = ContactSubmissionResource::class;
}
