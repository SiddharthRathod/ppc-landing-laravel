<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

// HasLabel + HasColor: Filament uses these for badges, filters and exports.
enum CrmStatus: string implements HasColor, HasLabel
{
    case Pending = 'pending';   // saved, CRM job queued
    case Sent = 'sent';         // accepted by the CRM
    case Failed = 'failed';     // all retries used up
    case Skipped = 'skipped';   // CRM switched off (CRM_ENABLED=false)

    public function getLabel(): string
    {
        return ucfirst($this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Sent => 'success',
            self::Failed => 'danger',
            self::Skipped => 'gray',
        };
    }

    /**
     * Statuses an admin can send to the CRM again.
     */
    public function canRetry(): bool
    {
        return in_array($this, [self::Failed, self::Skipped], true);
    }
}
