<?php

namespace App\Filament\Blocks;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Fieldset;

/**
 * Fields used by several page blocks, so every block uploads images
 * the same way and buttons always have a label + link.
 */
class Fields
{
    public static function image(string $name = 'image', string $label = 'Image'): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->image()
            ->disk('public')
            ->directory('pages')
            ->maxSize(2048);
    }

    public static function imageAlt(string $name = 'image_alt'): TextInput
    {
        return TextInput::make($name)
            ->label('Image description (alt text)')
            ->maxLength(255)
            ->helperText('For screen readers and Google. Describe what the image shows.');
    }

    public static function eyebrow(): TextInput
    {
        return TextInput::make('eyebrow')
            ->helperText('Small orange label above the heading.')
            ->maxLength(100);
    }

    public static function heading(bool $required = true): Textarea
    {
        return Textarea::make('heading')
            ->rows(2)
            ->required($required)
            ->maxLength(255)
            ->helperText('A new line in the box becomes a line break on the page.');
    }

    public static function text(string $name = 'text', string $label = 'Text'): Textarea
    {
        return Textarea::make($name)
            ->label($label)
            ->rows(3)
            ->maxLength(1000);
    }

    /**
     * A button: label + link. Saved as {prefix}_label and {prefix}_url.
     */
    public static function button(string $prefix, string $legend): Fieldset
    {
        return Fieldset::make($legend)
            ->columns(2)
            ->components([
                TextInput::make("{$prefix}_label")
                    ->label('Label')
                    ->maxLength(100),
                TextInput::make("{$prefix}_url")
                    ->label('Link')
                    ->maxLength(255)
                    ->helperText('/contact, #services or a full URL'),
            ]);
    }
}
