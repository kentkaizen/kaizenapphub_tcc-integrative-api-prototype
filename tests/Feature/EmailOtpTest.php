<?php

namespace Tests\Feature;

use App\Models\EmailOtp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class EmailOtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_otp_can_be_sent_and_stored(): void
    {
        $this->configureRepohive();
        Http::fake([
            'https://repohive.com/api/email/send' => Http::response(['message' => 'Email sent successfully.']),
        ]);

        $this->post(route('otp.email.send'), [
            'email' => 'Student@Example.com',
        ])->assertRedirect(route('otp.email.verify'))
            ->assertSessionHas('success')
            ->assertSessionHas('otp_email', 'student@example.com');

        $otp = EmailOtp::query()->first();

        $this->assertNotNull($otp);
        $this->assertSame('student@example.com', $otp->email);
        $this->assertMatchesRegularExpression('/^\d{6}$/', $otp->code);
        $this->assertTrue($otp->expires_at->isFuture());
        $this->assertSame(0, $otp->attempts);

        Http::assertSent(fn ($request) => $request->url() === 'https://repohive.com/api/email/send'
            && $request['to'] === 'student@example.com'
            && $request['subject'] === 'Verify your Kaizen App Hub account'
            && str_contains($request['html'], $otp->code)
            && str_contains($request['text'], $otp->code));
    }

    public function test_email_otp_can_be_verified(): void
    {
        $otp = EmailOtp::create([
            'email' => 'student@example.com',
            'code' => '123456',
            'expires_at' => now()->addMinutes(5),
            'attempts' => 0,
        ]);

        $this->post(route('otp.email.verify.store'), [
            'email' => 'student@example.com',
            'code' => '123456',
        ])->assertRedirect(route('otp.email.verify'))
            ->assertSessionHas('success', 'Email address verified.');

        $this->assertNotNull($otp->fresh()->verified_at);
    }

    public function test_invalid_email_codes_are_attempt_limited(): void
    {
        $otp = EmailOtp::create([
            'email' => 'student@example.com',
            'code' => '123456',
            'expires_at' => now()->addMinutes(5),
            'attempts' => 4,
        ]);

        $this->from(route('otp.email.verify'))->post(route('otp.email.verify.store'), [
            'email' => 'student@example.com',
            'code' => '000000',
        ])->assertRedirect(route('otp.email.verify'))
            ->assertSessionHasErrors('code');

        $this->assertSame(5, $otp->fresh()->attempts);

        $this->from(route('otp.email.verify'))->post(route('otp.email.verify.store'), [
            'email' => 'student@example.com',
            'code' => '123456',
        ])->assertRedirect(route('otp.email.verify'))
            ->assertSessionHasErrors('code');
    }

    public function test_email_resend_has_a_cooldown(): void
    {
        $this->configureRepohive();
        Http::fake([
            'https://repohive.com/api/email/send' => Http::response(['message' => 'Email sent successfully.']),
        ]);

        EmailOtp::create([
            'email' => 'student@example.com',
            'code' => '123456',
            'expires_at' => now()->addMinutes(5),
            'attempts' => 0,
        ]);

        $this->from(route('otp.email.verify'))->post(route('otp.email.resend'), [
            'email' => 'student@example.com',
        ])->assertRedirect(route('otp.email.verify'))
            ->assertSessionHasErrors('email');

        Http::assertNothingSent();
    }

    private function configureRepohive(): void
    {
        config([
            'services.repohive_email.base_url' => 'https://repohive.com/api',
            'services.repohive_email.token' => 'test-token',
        ]);
    }
}
