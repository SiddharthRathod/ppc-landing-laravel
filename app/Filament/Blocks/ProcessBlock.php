<?php

namespace App\Filament\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;

class ProcessBlock
{
    public static function make(): Block
    {
        return Block::make('process')
            ->label('Process steps')
            ->icon(Heroicon::OutlinedListBullet)
            ->schema([
                Fields::eyebrow(),
                Fields::heading(),
                Repeater::make('steps')
                    ->helperText('Numbered automatically. The last step is highlighted in orange.')
                    ->columns(2)
                    ->reorderable()
                    ->maxItems(6)
                    ->addActionLabel('Add step')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(50),
                        TextInput::make('text')
                            ->maxLength(255),
                    ]),
            ]);
    }
}
