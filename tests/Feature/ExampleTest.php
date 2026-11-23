<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_unconfigured_store_redirects_to_initial_setup()
    {
        $response = $this->get('/');

        $response->assertRedirect(route('register'));
    }

    public function test_seeded_storefront_returns_successful_response()
    {
        $this->seed();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Torray Store');
    }
}
