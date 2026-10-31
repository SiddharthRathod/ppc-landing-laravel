<?php

namespace App\Filament\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Support\Icons\Heroicon;

class ServicesBlock
{
    public static function make(): Block
    {
        return Block::make('services')
            ->label('Services')
            ->icon(Heroicon::OutlinedSquares2x2)
            ->schema([
                Fields::eyebrow(),
                Fields::heading(),
                Fields::text(),
                Repeater::make('cards')
                    ->label('Service cards')
                    ->collapsible()
                    ->collapsed()
                    ->reorderable()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->addActionLabel('Add service')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(100),
                        Fields::text('text', 'Short description'),
                        Fields::image(),
                        TextInput::make('url')
                            ->label('Link (optional)')
                            ->maxLength(255),
                        // A card with sub-images is shown as the wide grey card (like Photography).
                        Fieldset::make('Wide card with 4 photos (optional)')
                            ->components([
                                TextInput::make('badge')
                                    ->maxLength(30)
                                    ->placeholder('4 SPECIALISMS'),
                                Repeater::make('sub_images')
                                    ->label('Photos')
                                    ->grid(2)
                                    ->maxItems(4)
                                    ->addActionLabel('Add photo')
                                    ->schema([
                                        Fields::image()->required(),
                                        TextInput::make('label')
                                            ->required()
                                            ->maxLength(50),
                                    ]),
                            ]),
                    ]),
                TextInput::make('cta_text')
                    ->label('Dark band text')
                    ->maxLength(255),
                Fields::button('cta_primary', 'Dark band: outlined button'),
                Fields::button('cta_secondary', 'Dark band: orange button'),
            ]);
    }
}
