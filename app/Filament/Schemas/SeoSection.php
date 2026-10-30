<?php

namespace App\Filament\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;

/**
 * SEO fields shared by pages and posts (same column names on both tables).
 */
class SeoSection
{
    public static function make(): Section
    {
        return Section::make('SEO')
            ->description('Leave empty to use the title, excerpt and image.')
            ->collapsible()
            ->components([
                TextInput::make('meta_title')
                    ->maxLength(255)
                    ->helperText('Shown in Google results and the browser tab. About 60 characters.'),
                Textarea::make('meta_description')
                    ->rows(3)
                    ->maxLength(500)
                    ->helperText('Shown under the title in Google. About 155 characters.'),
                TextInput::make('canonical_url')
                    ->label('Canonical URL')
                    ->url()
                    ->maxLength(255)
                    ->helperText('Only if this content also lives at another URL. Empty = this page\'s own URL.'),
                FileUpload::make('og_image')
                    ->label('Social sharing image')
                    ->image()
                    ->disk('public')
                    ->directory('seo')
                    ->maxSize(2048)
                    ->helperText('Shown when the link is shared on WhatsApp, LinkedIn or X. 1200 × 630 px.'),
            ]);
    }
}
