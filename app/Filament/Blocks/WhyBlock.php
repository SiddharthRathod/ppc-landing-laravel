<?php

namespace App\Filament\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;

class WhyBlock
{
    public static function make(): Block
    {
        return Block::make('why')
            ->label('Why us')
            ->icon(Heroicon::OutlinedCheckBadge)
            ->schema([
                Fields::image(),
                Fields::imageAlt(),
                Fields::eyebrow(),
                Fields::heading(),
                Repeater::make('points')
                    ->columns(2)
                    ->reorderable()
                    ->addActionLabel('Add point')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(100),
                        TextInput::make('text')
                            ->maxLength(255),
                    ]),
                TextInput::make('closing')
                    ->label('Closing line')
                    ->maxLength(255),
            ]);
    }
}
