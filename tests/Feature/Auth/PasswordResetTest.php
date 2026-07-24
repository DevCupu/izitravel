<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_routes_are_disabled(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(404);
    }
}
