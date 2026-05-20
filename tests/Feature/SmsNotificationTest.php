<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SmsNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_sends_a_welcome_sms(): void
    {
        $this->configureRepohive();
        Http::fake([
            'https://repohive.com/api/messages' => Http::response(['queued' => true]),
        ]);

        $this->post(route('register.store'), [
            'name' => 'Student User',
            'email' => 'student@example.com',
            'phone' => '+63 900 000 0000',
            'password' => 'password',
        ])->assertRedirect(route('mailbox'))
            ->assertSessionHas('success', 'Account created. Welcome SMS sent.');

        $this->assertDatabaseHas('users', [
            'name' => 'Student User',
            'email' => 'student@example.com',
            'phone' => '+639000000000',
        ]);

        Http::assertSent(fn ($request) => $request->url() === 'https://repohive.com/api/messages'
            && $request['phone'] === '+639000000000'
            && str_contains($request['message'], 'Welcome to Kaizen App Hub')
            && str_contains($request['message'], 'Student User'));
    }

    public function test_authenticated_user_can_send_a_reminder_sms(): void
    {
        $this->configureRepohive();
        Http::fake([
            'https://repohive.com/api/messages' => Http::response(['queued' => true]),
        ]);

        $user = User::factory()->create([
            'phone' => '+639000000000',
        ]);

        $this->actingAs($user)
            ->from(route('mailbox'))
            ->post(route('sms.reminder'))
            ->assertRedirect(route('mailbox'))
            ->assertSessionHas('success', 'Reminder SMS sent.');

        Http::assertSent(fn ($request) => $request->url() === 'https://repohive.com/api/messages'
            && $request['phone'] === '+639000000000'
            && str_contains($request['message'], 'Reminder from Kaizen App Hub'));
    }

    public function test_reminder_sms_requires_a_phone_number(): void
    {
        Http::fake();

        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('mailbox'))
            ->post(route('sms.reminder'))
            ->assertRedirect(route('mailbox'))
            ->assertSessionHasErrors('phone');

        Http::assertNothingSent();
    }

    private function configureRepohive(): void
    {
        config([
            'services.repohive_sms.base_url' => 'https://repohive.com/api',
            'services.repohive_sms.token' => 'test-token',
        ]);
    }
}
