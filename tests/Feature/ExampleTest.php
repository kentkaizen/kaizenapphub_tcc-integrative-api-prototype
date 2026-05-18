<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Welcome to Kaizen App Hub');
    }

    public function test_prototype_routes_render_successfully(): void
    {
        $routes = [
            '/login' => 'Sign in',
            '/register' => 'Create account',
            '/otp/phone' => 'Send OTP to Phone',
            '/otp/email' => 'Send OTP to Email',
            '/otp/verify' => 'Validate OTP',
        ];

        foreach ($routes as $uri => $text) {
            $this->get($uri)
                ->assertStatus(200)
                ->assertSee($text);
        }

        $user = User::factory()->create();

        $protectedRoutes = [
            '/mailbox' => 'Kaizen App Hub',
            '/ai-chatbot' => 'Kaizen AI Assistant',
        ];

        foreach ($protectedRoutes as $uri => $text) {
            $this->actingAs($user)
                ->get($uri)
                ->assertStatus(200)
                ->assertSee($text);
        }
    }

    public function test_dashboard_routes_require_authentication(): void
    {
        $this->get('/mailbox')->assertRedirect('/login');
        $this->get('/ai-chatbot')->assertRedirect('/login');
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_user_can_login_with_database_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'student@example.com',
        ]);

        $this->post('/login', [
            'email' => 'student@example.com',
            'password' => 'password',
        ])->assertRedirect(route('mailbox'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_register_and_start_a_session(): void
    {
        $this->post('/register', [
            'name' => 'Student User',
            'email' => 'student@example.com',
            'password' => 'password',
        ])->assertRedirect(route('mailbox'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'Student User',
            'email' => 'student@example.com',
        ]);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }

    public function test_legacy_static_html_paths_redirect_to_laravel_routes(): void
    {
        $this->get('/index.html')->assertRedirect('/');
        $this->get('/otp-phone.html')->assertRedirect('/otp/phone');
        $this->get('/otp-email.html')->assertRedirect('/otp/email');
        $this->get('/validate-otp.html')->assertRedirect('/otp/verify');
        $this->get('/mailbox.html')->assertRedirect('/mailbox');
        $this->get('/ai-chatbot.html')->assertRedirect('/ai-chatbot');
    }
}
