<?php

namespace Tests\Feature;

use App\Contracts\CrmClient;
use App\Enums\CrmStatus;
use App\Jobs\SendSubmissionToCrm;
use App\Models\ContactSubmission;
use App\Services\Crm\LogCrmClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use RuntimeException;
use Tests\TestCase;

class SendSubmissionToCrmTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_driver_is_the_log_client(): void
    {
        $this->assertInstanceOf(LogCrmClient::class, app(CrmClient::class));
    }

    public function test_successful_send_marks_submission_as_sent(): void
    {
        $submission = ContactSubmission::factory()->create();

        (new SendSubmissionToCrm($submission))->handle(app(CrmClient::class));

        $submission->refresh();
        $this->assertSame(CrmStatus::Sent, $submission->crm_status);
        $this->assertSame('log-'.$submission->id, $submission->crm_response);
        $this->assertNotNull($submission->sent_at);
    }

    public function test_already_sent_submission_is_not_sent_again(): void
    {
        $submission = ContactSubmission::factory()->create(['crm_status' => CrmStatus::Sent]);

        $crm = $this->mock(CrmClient::class, function (MockInterface $mock) {
            $mock->shouldNotReceive('send');
        });

        (new SendSubmissionToCrm($submission))->handle($crm);
    }

    public function test_crm_error_is_rethrown_so_the_queue_retries(): void
    {
        $submission = ContactSubmission::factory()->create();

        $crm = $this->mock(CrmClient::class, function (MockInterface $mock) {
            $mock->shouldReceive('send')->andThrow(new RuntimeException('CRM is down'));
        });

        $this->expectException(RuntimeException::class);

        try {
            (new SendSubmissionToCrm($submission))->handle($crm);
        } finally {
            // Still pending: the queue will try again.
            $this->assertSame(CrmStatus::Pending, $submission->refresh()->crm_status);
        }
    }

    public function test_marks_submission_as_failed_after_the_last_try(): void
    {
        $submission = ContactSubmission::factory()->create();

        (new SendSubmissionToCrm($submission))->failed(new RuntimeException('CRM is down'));

        $submission->refresh();
        $this->assertSame(CrmStatus::Failed, $submission->crm_status);
        $this->assertSame('CRM is down', $submission->crm_response);
    }

    public function test_job_retries_with_backoff(): void
    {
        $job = new SendSubmissionToCrm(ContactSubmission::factory()->create());

        $this->assertSame(3, $job->tries);
        $this->assertSame([60, 300], $job->backoff);
    }
}
