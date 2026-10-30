<?php

namespace App\Enums;

enum CrmStatus: string
{
    case Pending = 'pending';   // saved, CRM job queued
    case Sent = 'sent';         // accepted by the CRM
    case Failed = 'failed';     // all retries used up
    case Skipped = 'skipped';   // CRM switched off (CRM_ENABLED=false)
}
