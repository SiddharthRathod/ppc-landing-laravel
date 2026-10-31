<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * Where the sales team is with an enquiry (separate from CrmStatus, which is the CRM sync).
 */
enum EnquiryStatus: string implements HasColor, HasLabel
{
    case New = 'new';             // just received
    case Contacted = 'contacted'; // a producer has replied
    case Closed = 'closed';       // won, lost or no longer relevant

    public function getLabel(): string
    {
        return ucfirst($this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::New => 'info',
            self::Contacted => 'warning',
            self::Closed => 'success',
        };
    }
}
