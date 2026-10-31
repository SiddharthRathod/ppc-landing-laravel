<?php

namespace App\Filament\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Support\Icons\Heroicon;

class TestimonialBlock
{
    public static function make(): Block
    {
        return Block::make('testimonial')
            ->label('Testimonial')
            ->icon(Heroicon::OutlinedChatBubbleBottomCenterText)
            ->schema([
                Textarea::make('quote')
                    ->rows(2)
                    ->required()
                    ->maxLength(500)
                    ->helperText('Without quotation marks; they are added on the page.'),
                TextInput::make('author')
                    ->maxLength(255)
                    ->placeholder('Name, job title, company'),
                Fields::image('image', 'Background photo'),
                Fields::button('primary', 'Orange button'),
                Fields::button('secondary', 'Outlined button'),
            ]);
    }
}
