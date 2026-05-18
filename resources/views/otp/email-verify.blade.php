@extends('layouts.app')

@section('title', 'Validate Email OTP')
@section('bodyClass', 'auth-surface')

@section('content')
<main class="app-shell">
    <section class="auth-view auth-view-compact">
        <div class="auth-intro">
            <span class="brand-chip">Email OTP</span>
            <h1>Enter the six-digit email code.</h1>
            <p>Codes expire automatically, and repeated invalid attempts are blocked.</p>
        </div>

        <section class="card glass-card">
            <header class="card-header">
                <div class="brand-row">
                    <span class="brand-mark" aria-hidden="true">OTP</span>
                    <span class="brand">Email Verification</span>
                </div>
                <h1>Validate Email OTP</h1>
                <p class="muted">
                    Code sent to:
                    <strong>{{ $email ?: 'your email address' }}</strong>
                </p>
            </header>

            @if (session('success'))
                <div class="success" role="status">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="auth-alert" role="alert">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('otp.email.verify.store') }}">
                @csrf

                <label for="email">Email Address</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    placeholder="example@company.com"
                    autocomplete="email"
                    value="{{ old('email', $email) }}"
                    required
                >

                <label for="code">Verification Code</label>
                <input
                    id="code"
                    name="code"
                    type="text"
                    inputmode="numeric"
                    maxlength="6"
                    pattern="[0-9]{6}"
                    placeholder="123456"
                    autocomplete="one-time-code"
                    value="{{ old('code') }}"
                    required
                >

                <button class="btn primary" type="submit">Verify OTP</button>
            </form>

            <form method="POST" action="{{ route('otp.email.resend') }}">
                @csrf
                <input type="hidden" name="email" value="{{ old('email', $email) }}">
                <button class="btn light" type="submit">Resend OTP</button>
            </form>

            <a class="link subtle-link" href="{{ route('otp.email') }}">Use another email</a>
            <a class="link subtle-link" href="{{ route('home') }}">Back to hub</a>
        </section>
    </section>
</main>
@endsection
