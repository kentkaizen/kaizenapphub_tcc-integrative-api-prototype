<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Welcome to RepoHive');
    }

    public function test_prototype_routes_render_successfully(): void
    {
        $routes = [
            '/login' => 'Sign in',
            '/register' => 'Create account',
            '/otp/phone' => 'Send OTP to Phone',
            '/otp/email' => 'Send OTP to Email',
            '/otp/verify' => 'Validate OTP',
            '/mailbox' => 'RepoHive',
            '/ai-chatbot' => 'RepoHive AI Assistant',
        ];

        foreach ($routes as $uri => $text) {
            $this->get($uri)
                ->assertStatus(200)
                ->assertSee($text);
        }
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
