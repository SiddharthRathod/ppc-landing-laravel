<?php

namespace App\Filament\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;

/**
 * "Get a quote" band. Phone, email and WhatsApp come from Site settings.
 */
class ContactCtaBlock
{
    public static function make(): Block
    {
        return Block::make('contact_cta')
            ->label('Quote call-to-action')
            ->icon(Heroicon::OutlinedPhone)
            ->schema([
                Fields::image(),
                Fields::imageAlt(),
                Fields::eyebrow(),
                Fields::heading(),
                TextInput::make('heading_ar')
                    ->label('Arabic line')
                    ->extraInputAttributes(['dir' => 'rtl', 'lang' => 'ar'])
                    ->maxLength(255),
                Fields::button('primary', 'Orange button'),
            ]);
    }
}
