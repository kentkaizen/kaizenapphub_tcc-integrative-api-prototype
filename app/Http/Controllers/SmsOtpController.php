<?php

namespace App\Http\Controllers;

use App\Models\SmsOtp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class SmsOtpController extends Controller
{
    private const CODE_LENGTH = 6;

    private const EXPIRES_IN_MINUTES = 5;

    private const MAX_ATTEMPTS = 5;

    private const RESEND_COOLDOWN_SECONDS = 60;

    public function create(): View
    {
        return view('otp.phone');
    }

    public function verifyForm(Request $request): View
    {
        return view('otp.verify', [
            'phone' => old('phone', $request->session()->get('otp_phone')),
        ]);
    }

    public function send(Request $request): RedirectResponse
    {
        $phone = $this->validatedPhone($request);

        $this->ensureCooldownHasPassed($phone);

        $otp = SmsOtp::create([
            'phone' => $phone,
            'code' => $this->generateCode(),
            'expires_at' => now()->addMinutes(self::EXPIRES_IN_MINUTES),
            'attempts' => 0,
        ]);

        try {
            $this->sendSms($otp);
        } catch (ValidationException $exception) {
            $otp->delete();

            throw $exception;
        }

        $request->session()->put('otp_phone', $phone);

        return redirect()
            ->route('otp.verify')
            ->with('success', 'OTP sent. Please check your phone.');
    }

    public function verify(Request $request): RedirectResponse
    {
        $phone = $this->validatedPhone($request);
        $validated = $request->validate([
            'code' => ['required', 'digits:'.self::CODE_LENGTH],
        ]);

        $otp = $this->latestOtpFor($phone);

        if (! $otp) {
            throw ValidationException::withMessages([
                'code' => 'No OTP request was found for this phone number.',
            ]);
        }

        if ($otp->isExpired()) {
            throw ValidationException::withMessages([
                'code' => 'This OTP has expired. Please request a new code.',
            ]);
        }

        if ($otp->attempts >= self::MAX_ATTEMPTS) {
            throw ValidationException::withMessages([
                'code' => 'Too many verification attempts. Please request a new code.',
            ]);
        }

        $otp->attempts++;

        if (! hash_equals($otp->code, $validated['code'])) {
            $otp->timestamps = false;
            $otp->save();

            $remaining = max(0, self::MAX_ATTEMPTS - $otp->attempts);

            throw ValidationException::withMessages([
                'code' => $remaining > 0
                    ? "Invalid OTP. {$remaining} attempts remaining."
                    : 'Too many verification attempts. Please request a new code.',
            ]);
        }

        $otp->verified_at = now();
        $otp->save();

        $request->session()->put('otp_verified_phone', $phone);
        $request->session()->put('otp_phone', $phone);

        return redirect()
            ->route('otp.verify')
            ->with('success', 'Phone number verified.');
    }

    public function resend(Request $request): RedirectResponse
    {
        $phone = $this->validatedPhone($request);
        $otp = $this->latestOtpFor($phone);

        if (! $otp) {
            throw ValidationException::withMessages([
                'phone' => 'Request an OTP before trying to resend.',
            ]);
        }

        $this->ensureCooldownHasPassed($phone);

        $otp->forceFill([
            'code' => $this->generateCode(),
            'expires_at' => now()->addMinutes(self::EXPIRES_IN_MINUTES),
            'verified_at' => null,
            'attempts' => 0,
        ]);

        $this->sendSms($otp);
        $otp->save();

        $request->session()->put('otp_phone', $phone);

        return redirect()
            ->route('otp.verify')
            ->with('success', 'A new OTP was sent.');
    }

    private function validatedPhone(Request $request): string
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:30', 'regex:/^\+?[0-9][0-9\s().-]{6,29}$/'],
        ]);

        return $this->normalizePhone($validated['phone']);
    }

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/(?!^\+)[^\d]/', '', trim($phone));
    }

    private function latestOtpFor(string $phone): ?SmsOtp
    {
        return SmsOtp::query()
            ->where('phone', $phone)
            ->whereNull('verified_at')
            ->latest('updated_at')
            ->latest('id')
            ->first();
    }

    private function ensureCooldownHasPassed(string $phone): void
    {
        $otp = $this->latestOtpFor($phone);

        if (! $otp) {
            return;
        }

        $cooldownEndsAt = $otp->updated_at->copy()->addSeconds(self::RESEND_COOLDOWN_SECONDS);

        if ($cooldownEndsAt->isFuture()) {
            $seconds = (int) ceil(now()->diffInSeconds($cooldownEndsAt));

            throw ValidationException::withMessages([
                'phone' => "Please wait {$seconds} seconds before requesting another OTP.",
            ]);
        }
    }

    private function generateCode(): string
    {
        return (string) random_int(10 ** (self::CODE_LENGTH - 1), (10 ** self::CODE_LENGTH) - 1);
    }

    private function sendSms(SmsOtp $otp): void
    {
        $token = config('services.repohive_sms.token');
        $baseUrl = config('services.repohive_sms.base_url');

        if (! $token || ! $baseUrl) {
            throw ValidationException::withMessages([
                'phone' => 'Repohive SMS is not configured.',
            ]);
        }

        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->asJson()
                ->timeout(30)
                ->post(rtrim($baseUrl, '/').'/messages', [
                    'phone' => $otp->phone,
                    'message' => "Your Kaizen App Hub verification code is {$otp->code}. Do not share it.",
                ]);
        } catch (Throwable $exception) {
            report($exception);

            throw ValidationException::withMessages([
                'phone' => 'Failed to send OTP. Please try again.',
            ]);
        }

        if ($response->failed()) {
            report(new RuntimeException('Repohive SMS failed with status '.$response->status()));

            throw ValidationException::withMessages([
                'phone' => 'Failed to send OTP. Please try again.',
            ]);
        }
    }
}
