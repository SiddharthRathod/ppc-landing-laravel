<?php

namespace App\Filament\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;

/**
 * The Contact Us form. The fields themselves are fixed (they match
 * StoreContactRequest); the admin edits the text around them.
 */
class ContactFormBlock
{
    public static function make(): Block
    {
        return Block::make('contact_form')
            ->label('Contact form')
            ->icon(Heroicon::OutlinedEnvelope)
            ->maxItems(1)
            ->schema([
                Fields::eyebrow(),
                Fields::heading(),
                Fields::text(),
                TextInput::make('heading_ar')
                    ->label('Arabic line')
                    ->extraInputAttributes(['dir' => 'rtl', 'lang' => 'ar'])
                    ->maxLength(255),
            ]);
    }
}
