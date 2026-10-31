<?php

namespace Tests\Feature;

use App\Enums\CrmStatus;
use App\Jobs\SendSubmissionToCrm;
use App\Models\ContactSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Record dispatched jobs instead of running them.
        Queue::fake();
    }

    /**
     * A submission that passes validation.
     *
     * @return array<string, string>
     */
    private function validData(): array
    {
        return [
            'name' => 'Ahmed Al-Harbi',
            'company' => 'Saedan',
            'email' => 'ahmed@example.sa',
            'phone' => '+966 55 111 6121',
            'service' => 'Corporate video',
            'message' => 'Two-day corporate shoot in Riyadh next month.',
            'website' => '', // honeypot, left empty by real visitors
        ];
    }

    public function test_valid_submission_is_saved_and_queued_for_the_crm(): void
    {
        $response = $this->from(route('contact'))->post(route('contact.store'), $this->validData());

        $response->assertRedirect(route('contact'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('contact_sent', true);

        $submission = ContactSubmission::sole();
        $this->assertSame('ahmed@example.sa', $submission->email);
        $this->assertSame(CrmStatus::Pending, $submission->crm_status);
        $this->assertNotNull($submission->ip_address);

        Queue::assertPushed(SendSubmissionToCrm::class, fn ($job) => $job->submission->is($submission));
    }

    public function test_submission_is_marked_skipped_when_crm_is_disabled(): void
    {
        config(['crm.enabled' => false]);

        $this->post(route('contact.store'), $this->validData());

        $this->assertSame(CrmStatus::Skipped, ContactSubmission::sole()->crm_status);
        Queue::assertNothingPushed();
    }

    public function test_required_fields_are_validated(): void
    {
        $response = $this->from(route('contact'))->post(route('contact.store'), []);

        $response->assertRedirect(route('contact'));
        $response->assertSessionHasErrors(['name', 'email', 'phone', 'message']);
        $response->assertSessionMissing('contact_sent');
        $this->assertDatabaseCount('contact_submissions', 0);
    }

    public function test_optional_fields_can_be_left_empty(): void
    {
        $data = array_merge($this->validData(), ['company' => '', 'service' => '']);

        $response = $this->from(route('contact'))->post(route('contact.store'), $data);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('contact_sent', true);
        $this->assertDatabaseCount('contact_submissions', 1);
    }

    public function test_submission_with_honeypot_filled_is_rejected(): void
    {
        $data = array_merge($this->validData(), ['website' => 'https://spam.example']);

        $response = $this->from(route('contact'))->post(route('contact.store'), $data);

        $response->assertSessionHasErrors('website');
        $response->assertSessionMissing('contact_sent');
        $this->assertDatabaseCount('contact_submissions', 0);
        Queue::assertNothingPushed();
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validPhones(): array
    {
        return [
            'international with spaces' => ['+966 55 111 6121'],
            'local with dashes' => ['055-111-6121'],
            'brackets' => ['(055) 111 6121'],
            'digits only' => ['966551116121'],
            'uk number' => ['+44 20 7946 0958'],
        ];
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidPhones(): array
    {
        return [
            'letters' => ['call me maybe'],
            'too short' => ['12345'],
            'too long' => ['+966 55 111 6121 1234 5678'],
            'plus in the middle' => ['966+551116121'],
            'other characters' => ['055/111/6121'],
        ];
    }

    #[DataProvider('validPhones')]
    public function test_common_phone_formats_are_accepted(string $phone): void
    {
        $this->post(route('contact.store'), array_merge($this->validData(), ['phone' => $phone]))
            ->assertSessionHasNoErrors();
    }

    #[DataProvider('invalidPhones')]
    public function test_invalid_phone_numbers_are_rejected(string $phone): void
    {
        $this->post(route('contact.store'), array_merge($this->validData(), ['phone' => $phone]))
            ->assertSessionHasErrors('phone');

        $this->assertDatabaseCount('contact_submissions', 0);
    }
}
