<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_protected_pages(): void
    {
        $this->get('/home')->assertRedirect(route('login'));
        $this->get('/admin/dashboard')->assertRedirect(route('login'));
    }

    public function test_login_page_is_available_and_valid_credentials_authenticate(): void
    {
        $user = User::factory()->create([
            'password' => 'secret-password',
        ]);

        $this->get(route('login'))->assertOk();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'secret-password',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_password_is_hashed_when_saved(): void
    {
        $user = User::factory()->create([
            'password' => 'secret-password',
        ]);

        $this->assertNotSame('secret-password', $user->password);
        $this->assertTrue(Hash::check('secret-password', $user->password));
    }

    public function test_logout_invalidates_authentication_and_protected_pages_redirect_again(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/admin/dashboard')->assertOk();
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));

        $this->post(route('logout'))->assertRedirect(route('login'));

        $this->assertGuest();
        $this->get('/admin/dashboard')->assertRedirect(route('login'));
    }
}
