<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\RepohiveSms;
use App\Support\PhoneNumber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    public function store(Request $request, RepohiveSms $sms): RedirectResponse
    {
        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'phone' => PhoneNumber::rules(),
            'password' => ['required', 'string', 'min:8'],
        ]);

        $attributes['phone'] = PhoneNumber::normalize($attributes['phone']);

        $user = User::create($attributes);

        Auth::login($user);
        $request->session()->regenerate();

        try {
            $sms->send(
                $user->phone,
                "Welcome to Kaizen App Hub, {$user->name}. Your secure mailbox is ready.",
                'phone',
                'Failed to send welcome SMS. Please try again.'
            );
        } catch (ValidationException) {
            return redirect()
                ->route('mailbox')
                ->with('warning', 'Account created, but the welcome SMS could not be sent.');
        }

        return redirect()
            ->route('mailbox')
            ->with('success', 'Account created. Welcome SMS sent.');
    }
}
