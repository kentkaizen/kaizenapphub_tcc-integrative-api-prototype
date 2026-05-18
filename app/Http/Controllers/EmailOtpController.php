<?php

namespace App\Http\Controllers;

use App\Models\EmailOtp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class EmailOtpController extends Controller
{
    private const CODE_LENGTH = 6;

    private const EXPIRES_IN_MINUTES = 5;

    private const MAX_ATTEMPTS = 5;

    private const RESEND_COOLDOWN_SECONDS = 60;

    public function create(): View
    {
        return view('otp.email');
    }

    public function verifyForm(Request $request): View
    {
        return view('otp.email-verify', [
            'email' => old('email', $request->session()->get('otp_email')),
        ]);
    }

    public function send(Request $request): RedirectResponse
    {
        $email = $this->validatedEmail($request);

        $this->ensureCooldownHasPassed($email);

        $otp = EmailOtp::create([
            'email' => $email,
            'code' => $this->generateCode(),
            'expires_at' => now()->addMinutes(self::EXPIRES_IN_MINUTES),
            'attempts' => 0,
        ]);

        try {
            $this->sendEmail($otp);
        } catch (ValidationException $exception) {
            $otp->delete();

            throw $exception;
        }

        $request->session()->put('otp_email', $email);

        return redirect()
            ->route('otp.email.verify')
            ->with('success', 'OTP sent. Please check your email.');
    }

    public function verify(Request $request): RedirectResponse
    {
        $email = $this->validatedEmail($request);
        $validated = $request->validate([
            'code' => ['required', 'digits:'.self::CODE_LENGTH],
        ]);

        $otp = $this->latestOtpFor($email);

        if (! $otp) {
            throw ValidationException::withMessages([
                'code' => 'No OTP request was found for this email address.',
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

        $request->session()->put('otp_verified_email', $email);
        $request->session()->put('otp_email', $email);

        return redirect()
            ->route('otp.email.verify')
            ->with('success', 'Email address verified.');
    }

    public function resend(Request $request): RedirectResponse
    {
        $email = $this->validatedEmail($request);
        $otp = $this->latestOtpFor($email);

        if (! $otp) {
            throw ValidationException::withMessages([
                'email' => 'Request an OTP before trying to resend.',
            ]);
        }

        $this->ensureCooldownHasPassed($email);

        $otp->forceFill([
            'code' => $this->generateCode(),
            'expires_at' => now()->addMinutes(self::EXPIRES_IN_MINUTES),
            'verified_at' => null,
            'attempts' => 0,
        ]);

        $this->sendEmail($otp);
        $otp->save();

        $request->session()->put('otp_email', $email);

        return redirect()
            ->route('otp.email.verify')
            ->with('success', 'A new OTP was sent.');
    }

    private function validatedEmail(Request $request): string
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        return strtolower($validated['email']);
    }

    private function latestOtpFor(string $email): ?EmailOtp
    {
        return EmailOtp::query()
            ->where('email', $email)
            ->whereNull('verified_at')
            ->latest('updated_at')
            ->latest('id')
            ->first();
    }

    private function ensureCooldownHasPassed(string $email): void
    {
        $otp = $this->latestOtpFor($email);

        if (! $otp) {
            return;
        }

        $cooldownEndsAt = $otp->updated_at->copy()->addSeconds(self::RESEND_COOLDOWN_SECONDS);

        if ($cooldownEndsAt->isFuture()) {
            $seconds = (int) ceil(now()->diffInSeconds($cooldownEndsAt));

            throw ValidationException::withMessages([
                'email' => "Please wait {$seconds} seconds before requesting another OTP.",
            ]);
        }
    }

    private function generateCode(): string
    {
        return (string) random_int(10 ** (self::CODE_LENGTH - 1), (10 ** self::CODE_LENGTH) - 1);
    }

    private function sendEmail(EmailOtp $otp): void
    {
        $token = config('services.repohive_email.token');
        $baseUrl = config('services.repohive_email.base_url');

        if (! $token || ! $baseUrl) {
            throw ValidationException::withMessages([
                'email' => 'Repohive Email is not configured.',
            ]);
        }

        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->asJson()
                ->timeout(30)
                ->post(rtrim($baseUrl, '/').'/email/send', [
                    'to' => $otp->email,
                    'subject' => 'Verify your Kaizen App Hub account',
                    'html' => '<p>Your Kaizen App Hub verification code is <strong>'.$otp->code.'</strong>.</p><p>Do not share this code.</p>',
                    'text' => "Your Kaizen App Hub verification code is {$otp->code}. Do not share it.",
                ]);
        } catch (Throwable $exception) {
            report($exception);

            throw ValidationException::withMessages([
                'email' => 'Failed to send OTP. Please try again.',
            ]);
        }

        if ($response->failed()) {
            report(new RuntimeException('Repohive Email failed with status '.$response->status()));

            throw ValidationException::withMessages([
                'email' => 'Failed to send OTP. Please try again.',
            ]);
        }
    }
}
