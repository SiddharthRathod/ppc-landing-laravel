<?php

namespace App\Services;

use App\Enums\CrmStatus;
use App\Jobs\SendSubmissionToCrm;
use App\Models\ContactSubmission;

class ContactSubmissionService
{
    /**
     * Save the submission first, then queue the CRM sync.
     * Saving first means a CRM outage never loses a lead.
     *
     * @param  array<string, mixed>  $data  Validated form fields.
     */
    public function submit(array $data, ?string $ipAddress, ?string $userAgent): ContactSubmission
    {
        $crmEnabled = config('crm.enabled');

        $submission = ContactSubmission::create([
            ...$data,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'crm_status' => $crmEnabled ? CrmStatus::Pending : CrmStatus::Skipped,
        ]);

        if ($crmEnabled) {
            SendSubmissionToCrm::dispatch($submission);
        }

        return $submission;
    }
}
