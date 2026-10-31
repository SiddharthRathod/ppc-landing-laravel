<?php

namespace App\Filament\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;

class ClientsBlock
{
    public static function make(): Block
    {
        return Block::make('clients')
            ->label('Client logos')
            ->icon(Heroicon::OutlinedUserGroup)
            ->schema([
                TextInput::make('heading')
                    ->maxLength(100),
                Repeater::make('logos')
                    ->grid(3)
                    ->reorderable()
                    ->addActionLabel('Add logo')
                    ->schema([
                        Fields::image('image', 'Logo')->required(),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(100)
                            ->helperText('Used as the alt text.'),
                    ]),
            ]);
    }
}
