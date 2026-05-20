<?php

namespace App\Http\Controllers;

use App\Services\RepohiveSms;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SmsNotificationController extends Controller
{
    public function sendReminder(Request $request, RepohiveSms $sms): RedirectResponse
    {
        $user = $request->user();

        if (! $user->phone) {
            return back()->withErrors([
                'phone' => 'A phone number is required before sending SMS reminders.',
            ]);
        }

        $sms->send(
            $user->phone,
            'Reminder from Kaizen App Hub: please review your mailbox updates and pending verification tasks.',
            'phone',
            'Failed to send reminder SMS. Please try again.'
        );

        return back()->with('success', 'Reminder SMS sent.');
    }
}
