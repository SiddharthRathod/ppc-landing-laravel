<?php

namespace App\Filament\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;

class IndustriesBlock
{
    /**
     * Icons drawn in resources/views/sections/icons/{key}.blade.php.
     */
    public const ICONS = [
        'energy' => 'Energy (plant)',
        'construction' => 'Construction (crane)',
        'government' => 'Government (building)',
        'events' => 'Events (microphone)',
    ];

    public static function make(): Block
    {
        return Block::make('industries')
            ->label('Industries')
            ->icon(Heroicon::OutlinedBuildingOffice2)
            ->schema([
                Fields::eyebrow(),
                Fields::heading(),
                Fields::text(),
                Repeater::make('cards')
                    ->grid(2)
                    ->reorderable()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                    ->addActionLabel('Add industry')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(100),
                        Fields::text('text', 'Short description'),
                        Select::make('icon')
                            ->options(self::ICONS)
                            ->required(),
                    ]),
            ]);
    }
}
