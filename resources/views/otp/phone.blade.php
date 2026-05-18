@extends('layouts.app')

@section('title', 'Send Phone OTP')
@section('bodyClass', 'auth-surface')

@section('content')
<main class="app-shell">
    <section class="auth-view auth-view-compact">
        <div class="auth-intro">
            <span class="brand-chip">Phone OTP</span>
            <h1>Verify access with a phone number.</h1>
            <p>Repohive sends the SMS code while Laravel stores the OTP request for validation.</p>
        </div>

        <section class="card glass-card">
            <header class="card-header">
                <div class="brand-row">
                    <span class="brand-mark" aria-hidden="true">OTP</span>
                    <span class="brand">Phone Verification</span>
                </div>
                <h1>Send OTP to Phone</h1>
                <p class="muted">Enter your phone number to receive a 6-digit verification code.</p>
            </header>

            @if (session('success'))
                <div class="success" role="status">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="auth-alert" role="alert">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('otp.phone.send') }}">
                @csrf

                <label for="phone">Phone Number</label>
                <input
                    id="phone"
                    name="phone"
                    type="tel"
                    placeholder="+63 900 000 0000"
                    autocomplete="tel"
                    value="{{ old('phone', session('otp_phone')) }}"
                    required
                >

                <button class="btn primary" type="submit">Send OTP</button>
            </form>

            <a class="link" href="{{ route('otp.email') }}">Use email instead</a>
            <a class="link subtle-link" href="{{ route('home') }}">Back to hub</a>
        </section>
    </section>
</main>
@endsection
