<?php

namespace App\Filament\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Support\Icons\Heroicon;

/**
 * Top of the home page: heading, chips, two buttons, photo or video, and the stats row.
 * Rendered by resources/views/sections/hero.blade.php.
 */
class HeroBlock
{
    public static function make(): Block
    {
        return Block::make('hero')
            ->label('Hero')
            ->icon(Heroicon::OutlinedSparkles)
            ->maxItems(1)
            ->schema([
                Fields::eyebrow(),
                Fields::heading(),
                TextInput::make('heading_ar')
                    ->label('Arabic line (orange)')
                    ->extraInputAttributes(['dir' => 'rtl', 'lang' => 'ar'])
                    ->maxLength(255),
                Fields::text(),
                TagsInput::make('chips')
                    ->label('Service chips')
                    ->placeholder('Add a chip'),
                Fields::button('primary', 'Orange button'),
                Fields::button('secondary', 'Outlined button'),
                Fieldset::make('Photo or video')
                    ->columns(2)
                    ->components([
                        Fields::image()->required(),
                        Fields::imageAlt(),
                        FileUpload::make('video')
                            ->label('Background video (optional)')
                            ->acceptedFileTypes(['video/mp4'])
                            ->disk('public')
                            ->directory('pages/videos')
                            ->maxSize(20480)
                            ->helperText('MP4, up to 20 MB. Plays muted and looped over the photo; the photo shows while it loads.'),
                        TextInput::make('showreel_url')
                            ->label('Showreel link (optional)')
                            ->url()
                            ->maxLength(255)
                            ->helperText('YouTube or Vimeo URL. Adds a "Watch showreel" link.'),
                    ]),
                Repeater::make('stats')
                    ->columns(2)
                    ->maxItems(4)
                    ->addActionLabel('Add stat')
                    ->schema([
                        TextInput::make('value')
                            ->required()
                            ->maxLength(20)
                            ->placeholder('5,000+'),
                        TextInput::make('label')
                            ->required()
                            ->maxLength(100),
                    ]),
            ]);
    }
}
