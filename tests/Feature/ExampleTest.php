<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Root diarahkan ke /dashboard (terproteksi auth).
     */
    public function test_root_redirects_to_dashboard(): void
    {
        $this->get('/')->assertRedirect('/dashboard');
    }

    /**
     * Registrasi mandiri ditutup.
     */
    public function test_register_page_is_not_available(): void
    {
        $this->get('/register')->assertNotFound();
    }
}
