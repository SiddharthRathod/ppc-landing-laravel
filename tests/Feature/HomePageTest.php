<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_home_page_renders(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Your Story.');
    }

    public function test_contact_page_renders(): void
    {
        $response = $this->get(route('contact'));

        $response->assertOk();
        $response->assertSee('Get a quote within 24 hours');
    }
}
