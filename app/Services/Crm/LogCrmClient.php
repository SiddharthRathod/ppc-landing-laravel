<?php

namespace App\Services\Crm;

use App\Contracts\CrmClient;
use App\Models\ContactSubmission;
use Illuminate\Support\Facades\Log;

/**
 * Default driver until the real CRM is known: writes the lead to the log.
 */
class LogCrmClient implements CrmClient
{
    public function send(ContactSubmission $submission): string
    {
        Log::info('CRM lead', $submission->only([
            'id', 'name', 'company', 'email', 'phone', 'service', 'message',
        ]));

        return 'log-'.$submission->id;
    }
}
