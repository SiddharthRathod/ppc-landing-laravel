<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContactFormTest extends TestCase
{
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
        ];
    }

    public function test_valid_submission_redirects_back_with_success_flag(): void
    {
        $response = $this->from(route('contact'))->post(route('contact.store'), $this->validData());

        $response->assertRedirect(route('contact'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('contact_sent', true);
    }

    public function test_required_fields_are_validated(): void
    {
        $response = $this->from(route('contact'))->post(route('contact.store'), []);

        $response->assertRedirect(route('contact'));
        $response->assertSessionHasErrors(['name', 'email', 'phone', 'message']);
        $response->assertSessionMissing('contact_sent');
    }

    public function test_optional_fields_can_be_left_empty(): void
    {
        $data = array_merge($this->validData(), ['company' => '', 'service' => '']);

        $response = $this->from(route('contact'))->post(route('contact.store'), $data);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('contact_sent', true);
    }

    public function test_submission_with_honeypot_filled_is_rejected(): void
    {
        $data = array_merge($this->validData(), ['website' => 'https://spam.example']);

        $response = $this->from(route('contact'))->post(route('contact.store'), $data);

        $response->assertSessionHasErrors('website');
        $response->assertSessionMissing('contact_sent');
    }
}
