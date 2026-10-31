<?php

namespace App\Filament\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;

/**
 * Heading only: the cards are the 3 latest published blog posts.
 */
class BlogBlock
{
    public static function make(): Block
    {
        return Block::make('blog')
            ->label('Latest blog posts')
            ->icon(Heroicon::OutlinedNewspaper)
            ->maxItems(1)
            ->schema([
                Fields::eyebrow(),
                Fields::heading(),
                TextInput::make('link_label')
                    ->label('"All posts" link label')
                    ->maxLength(50),
            ]);
    }
}
