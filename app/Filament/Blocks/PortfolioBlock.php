<?php

namespace App\Filament\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;

class PortfolioBlock
{
    public static function make(): Block
    {
        return Block::make('portfolio')
            ->label('Portfolio (film strip)')
            ->icon(Heroicon::OutlinedFilm)
            ->schema([
                Fields::eyebrow(),
                Fields::heading(),
                Fields::text(),
                Fields::button('button', 'Button'),
                Repeater::make('frames')
                    ->helperText('The first 7 frames fill the film-strip layout (frame 1 is the large one).')
                    ->grid(2)
                    ->reorderable()
                    ->maxItems(7)
                    ->itemLabel(fn (array $state): ?string => $state['caption'] ?? null)
                    ->addActionLabel('Add frame')
                    ->schema([
                        Fields::image()->required(),
                        TextInput::make('caption')
                            ->required()
                            ->maxLength(100)
                            ->helperText('Also used as the alt text.'),
                    ]),
            ]);
    }
}
