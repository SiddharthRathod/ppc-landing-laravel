<?php

namespace Tests\Feature\Admin;

use App\Enums\CrmStatus;
use App\Enums\EnquiryStatus;
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

    public function test_default_columns_are_name_company_email_phone_and_service(): void
    {
        ContactSubmission::factory()->create();

        $list = Livewire::test(ListContactSubmissions::class);

        foreach (['name', 'company', 'email', 'phone', 'service', 'status'] as $column) {
            $list->assertCanRenderTableColumn($column);
        }

        // Hidden by default, but available under "Columns".
        foreach (['created_at', 'crm_status', 'sent_at'] as $column) {
            $list->assertCanNotRenderTableColumn($column);
        }
    }

    public function test_status_and_notes_can_be_updated_from_the_table(): void
    {
        $submission = ContactSubmission::factory()->create();

        Livewire::test(ListContactSubmissions::class)
            ->callAction(TestAction::make('updateStatus')->table($submission), [
                'status' => EnquiryStatus::Contacted->value,
                'notes' => 'Called on Sunday, sending a quote.',
            ])
            ->assertHasNoFormErrors();

        $submission->refresh();
        $this->assertSame(EnquiryStatus::Contacted, $submission->status);
        $this->assertSame('Called on Sunday, sending a quote.', $submission->notes);
    }

    public function test_status_can_be_updated_from_the_view_page(): void
    {
        $submission = ContactSubmission::factory()->create(['notes' => 'Old note']);

        Livewire::test(ViewContactSubmission::class, ['record' => $submission->id])
            ->callAction('updateStatus', ['status' => EnquiryStatus::Closed->value, 'notes' => 'Project booked.'])
            ->assertHasNoFormErrors();

        $submission->refresh();
        $this->assertSame(EnquiryStatus::Closed, $submission->status);
        $this->assertSame('Project booked.', $submission->notes);
    }

    public function test_update_form_opens_with_the_current_status_and_notes(): void
    {
        $submission = ContactSubmission::factory()->create(['status' => EnquiryStatus::Contacted, 'notes' => 'Old note']);

        Livewire::test(ViewContactSubmission::class, ['record' => $submission->id])
            ->mountAction('updateStatus')
            ->assertActionDataSet(['status' => EnquiryStatus::Contacted, 'notes' => 'Old note']);
    }

    public function test_status_is_required(): void
    {
        $submission = ContactSubmission::factory()->create();

        Livewire::test(ListContactSubmissions::class)
            ->callAction(TestAction::make('updateStatus')->table($submission), ['status' => null])
            ->assertHasFormErrors(['status' => 'required']);
    }

    public function test_list_can_be_filtered_by_status(): void
    {
        $new = ContactSubmission::factory()->create();
        $closed = ContactSubmission::factory()->create(['status' => EnquiryStatus::Closed]);

        Livewire::test(ListContactSubmissions::class)
            ->filterTable('status', EnquiryStatus::Closed->value)
            ->assertCanSeeTableRecords([$closed])
            ->assertCanNotSeeTableRecords([$new]);
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
