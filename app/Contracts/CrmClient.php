<?php

namespace App\Contracts;

use App\Models\ContactSubmission;

/**
 * Sends a lead to the CRM. One class per CRM (HubSpot, Zoho, Salesforce...);
 * the active one is chosen with CRM_DRIVER in config/crm.php.
 */
interface CrmClient
{
    /**
     * Send the submission to the CRM.
     *
     * @return string The CRM's reference for the lead (stored in crm_response).
     *
     * @throws \Throwable When the CRM rejects the lead or cannot be reached, so the job retries.
     */
    public function send(ContactSubmission $submission): string;
}
