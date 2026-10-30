<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * Not stored: worked out from posts.published_at (see Post::status()).
 */
enum PostStatus: string implements HasColor, HasLabel
{
    case Draft = 'draft';           // no publish date
    case Scheduled = 'scheduled';   // publish date in the future
    case Published = 'published';   // publish date has arrived

    public function getLabel(): string
    {
        return ucfirst($this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Scheduled => 'info',
            self::Published => 'success',
        };
    }
}
