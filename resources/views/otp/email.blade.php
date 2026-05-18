@extends('layouts.app')

@section('title', 'Send Email OTP')
@section('bodyClass', 'auth-surface')

@section('content')
<main class="app-shell">
    <section class="auth-view auth-view-compact">
        <div class="auth-intro">
            <span class="brand-chip">Email OTP</span>
            <h1>Verify access with an email address.</h1>
            <p>Repohive sends the email code while Laravel stores the OTP request for validation.</p>
        </div>

        <section class="card glass-card">
            <header class="card-header">
                <div class="brand-row">
                    <span class="brand-mark" aria-hidden="true">OTP</span>
                    <span class="brand">Email Verification</span>
                </div>
                <h1>Send OTP to Email</h1>
                <p class="muted">Enter your email address to receive a 6-digit verification code.</p>
            </header>

            @if (session('success'))
                <div class="success" role="status">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="auth-alert" role="alert">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('otp.email.send') }}">
                @csrf

                <label for="email">Email Address</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    placeholder="example@company.com"
                    autocomplete="email"
                    value="{{ old('email', session('otp_email')) }}"
                    required
                >

                <button class="btn primary" type="submit">Send OTP</button>
            </form>

            <a class="link" href="{{ route('otp.phone') }}">Use phone instead</a>
            <a class="link subtle-link" href="{{ route('home') }}">Back to hub</a>
        </section>
    </section>
</main>
@endsection
