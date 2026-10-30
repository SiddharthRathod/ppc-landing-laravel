<?php

namespace App\Jobs;

use App\Contracts\CrmClient;
use App\Enums\CrmStatus;
use App\Models\ContactSubmission;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class SendSubmissionToCrm implements ShouldQueue
{
    use Queueable;

    /**
     * Try 3 times in total before giving up.
     */
    public int $tries = 3;

    /**
     * Wait 1 minute before the 2nd try and 5 minutes before the 3rd,
     * so a short CRM outage doesn't use up all the tries.
     *
     * @var array<int, int>
     */
    public array $backoff = [60, 300];

    /**
     * If the submission was deleted (e.g. spam) before the job runs,
     * drop the job instead of failing it.
     */
    public bool $deleteWhenMissingModels = true;

    public function __construct(public ContactSubmission $submission)
    {
    }

    /**
     * Execute the job. The CrmClient is injected from the container.
     */
    public function handle(CrmClient $crm): void
    {
        // Never send the same lead twice (e.g. a manual retry after it went through).
        if ($this->submission->crm_status === CrmStatus::Sent) {
            return;
        }

        $reference = $crm->send($this->submission);

        $this->submission->update([
            'crm_status' => CrmStatus::Sent,
            'crm_response' => $reference,
            'sent_at' => now(),
        ]);
    }

    /**
     * Called by Laravel once all tries have failed.
     */
    public function failed(?Throwable $exception): void
    {
        $this->submission->update([
            'crm_status' => CrmStatus::Failed,
            'crm_response' => $exception?->getMessage(),
        ]);
    }
}
