<?php

namespace Tests\Feature;

use App\Models\SmsOtp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SmsOtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_phone_otp_can_be_sent_and_stored(): void
    {
        $this->configureRepohive();
        Http::fake([
            'https://repohive.com/api/messages' => Http::response(['queued' => true]),
        ]);

        $this->post(route('otp.phone.send'), [
            'phone' => '+63 900 000 0000',
        ])->assertRedirect(route('otp.verify'))
            ->assertSessionHas('success')
            ->assertSessionHas('otp_phone', '+639000000000');

        $otp = SmsOtp::query()->first();

        $this->assertNotNull($otp);
        $this->assertSame('+639000000000', $otp->phone);
        $this->assertMatchesRegularExpression('/^\d{6}$/', $otp->code);
        $this->assertTrue($otp->expires_at->isFuture());
        $this->assertSame(0, $otp->attempts);

        Http::assertSent(fn ($request) => $request->url() === 'https://repohive.com/api/messages'
            && $request['phone'] === '+639000000000'
            && str_contains($request['message'], $otp->code));
    }

    public function test_phone_otp_can_be_verified(): void
    {
        $otp = SmsOtp::create([
            'phone' => '+639000000000',
            'code' => '123456',
            'expires_at' => now()->addMinutes(5),
            'attempts' => 0,
        ]);

        $this->post(route('otp.verify.store'), [
            'phone' => '+639000000000',
            'code' => '123456',
        ])->assertRedirect(route('otp.verify'))
            ->assertSessionHas('success', 'Phone number verified.');

        $this->assertNotNull($otp->fresh()->verified_at);
    }

    public function test_invalid_codes_are_attempt_limited(): void
    {
        $otp = SmsOtp::create([
            'phone' => '+639000000000',
            'code' => '123456',
            'expires_at' => now()->addMinutes(5),
            'attempts' => 4,
        ]);

        $this->from(route('otp.verify'))->post(route('otp.verify.store'), [
            'phone' => '+639000000000',
            'code' => '000000',
        ])->assertRedirect(route('otp.verify'))
            ->assertSessionHasErrors('code');

        $this->assertSame(5, $otp->fresh()->attempts);

        $this->from(route('otp.verify'))->post(route('otp.verify.store'), [
            'phone' => '+639000000000',
            'code' => '123456',
        ])->assertRedirect(route('otp.verify'))
            ->assertSessionHasErrors('code');
    }

    public function test_resend_has_a_cooldown(): void
    {
        $this->configureRepohive();
        Http::fake([
            'https://repohive.com/api/messages' => Http::response(['queued' => true]),
        ]);

        SmsOtp::create([
            'phone' => '+639000000000',
            'code' => '123456',
            'expires_at' => now()->addMinutes(5),
            'attempts' => 0,
        ]);

        $this->from(route('otp.verify'))->post(route('otp.resend'), [
            'phone' => '+639000000000',
        ])->assertRedirect(route('otp.verify'))
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
