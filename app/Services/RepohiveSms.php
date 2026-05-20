<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Throwable;

class RepohiveSms
{
    public function send(
        string $phone,
        string $message,
        string $errorKey = 'phone',
        string $failureMessage = 'Failed to send SMS. Please try again.'
    ): void {
        $token = config('services.repohive_sms.token');
        $baseUrl = config('services.repohive_sms.base_url');

        if (! $token || ! $baseUrl) {
            throw ValidationException::withMessages([
                $errorKey => 'Repohive SMS is not configured.',
            ]);
        }

        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->asJson()
                ->timeout(30)
                ->post(rtrim($baseUrl, '/').'/messages', [
                    'phone' => $phone,
                    'message' => $message,
                ]);
        } catch (Throwable $exception) {
            report($exception);

            throw ValidationException::withMessages([
                $errorKey => $failureMessage,
            ]);
        }

        if ($response->failed()) {
            report(new RuntimeException('Repohive SMS failed with status '.$response->status()));

            throw ValidationException::withMessages([
                $errorKey => $failureMessage,
            ]);
        }
    }
}
