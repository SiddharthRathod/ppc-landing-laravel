<?php

namespace App\Filament\Resources\Pages\Pages;

use App\Filament\Resources\Pages\PageResource;
use Filament\Resources\Pages\ListRecords;

// No "Create" button: the pages (Home, Contact) are created by PageSeeder.
class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;
}
