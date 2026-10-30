<?php

namespace Tests\Feature\Admin;

use App\Enums\CrmStatus;
use App\Filament\Resources\ContactSubmissions\Pages\ListContactSubmissions;
use App\Filament\Resources\ContactSubmissions\Pages\ViewContactSubmission;
use App\Jobs\SendSubmissionToCrm;
use App\Models\ContactSubmission;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class ContactSubmissionResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
        Queue::fake();
    }

    public function test_list_page_shows_submissions(): void
    {
        $submissions = ContactSubmission::factory()->count(3)->create();

        $this->get('/admin/contact-submissions')->assertOk();

        Livewire::test(ListContactSubmissions::class)
            ->assertCanSeeTableRecords($submissions);
    }

    public function test_list_can_be_filtered_by_crm_status(): void
    {
        $failed = ContactSubmission::factory()->create(['crm_status' => CrmStatus::Failed]);
        $sent = ContactSubmission::factory()->create(['crm_status' => CrmStatus::Sent]);

        Livewire::test(ListContactSubmissions::class)
            ->filterTable('crm_status', CrmStatus::Failed->value)
            ->assertCanSeeTableRecords([$failed])
            ->assertCanNotSeeTableRecords([$sent]);
    }

    public function test_view_page_shows_the_message(): void
    {
        $submission = ContactSubmission::factory()->create(['message' => 'Drone shoot in Jeddah']);

        $this->get("/admin/contact-submissions/{$submission->id}")
            ->assertOk()
            ->assertSee('Drone shoot in Jeddah');
    }

    public function test_there_is_no_create_or_edit_page(): void
    {
        $submission = ContactSubmission::factory()->create();

        $this->get('/admin/contact-submissions/create')->assertNotFound();
        $this->get("/admin/contact-submissions/{$submission->id}/edit")->assertNotFound();
    }

    public function test_retry_requeues_a_failed_submission(): void
    {
        $submission = ContactSubmission::factory()->create([
            'crm_status' => CrmStatus::Failed,
            'crm_response' => 'CRM is down',
        ]);

        Livewire::test(ListContactSubmissions::class)
            ->callAction(TestAction::make('retryCrm')->table($submission));

        $submission->refresh();
        $this->assertSame(CrmStatus::Pending, $submission->crm_status);
        $this->assertNull($submission->crm_response);
        Queue::assertPushed(SendSubmissionToCrm::class, fn ($job) => $job->submission->is($submission));
    }

    public function test_retry_is_hidden_for_sent_submissions(): void
    {
        $submission = ContactSubmission::factory()->create(['crm_status' => CrmStatus::Sent]);

        Livewire::test(ViewContactSubmission::class, ['record' => $submission->id])
            ->assertActionHidden('retryCrm');
    }

    public function test_retry_is_hidden_when_the_crm_is_disabled(): void
    {
        config(['crm.enabled' => false]);
        $submission = ContactSubmission::factory()->create(['crm_status' => CrmStatus::Skipped]);

        Livewire::test(ViewContactSubmission::class, ['record' => $submission->id])
            ->assertActionHidden('retryCrm');
    }

    public function test_retry_from_the_view_page(): void
    {
        $submission = ContactSubmission::factory()->create(['crm_status' => CrmStatus::Skipped]);

        Livewire::test(ViewContactSubmission::class, ['record' => $submission->id])
            ->callAction('retryCrm');

        $this->assertSame(CrmStatus::Pending, $submission->refresh()->crm_status);
        Queue::assertPushed(SendSubmissionToCrm::class);
    }
}
