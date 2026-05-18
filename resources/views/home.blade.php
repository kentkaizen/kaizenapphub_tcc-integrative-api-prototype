@extends('layouts.app')

@section('title', 'Kaizen App Hub')
@section('bodyClass', 'auth-surface')

@section('content')
<main class="app-shell">
    <section class="auth-view">
        <div class="auth-intro">
            <span class="brand-chip">Kaizen App Hub</span>
            <h1>Verification, mailbox, and AI support in one clean workspace.</h1>
            <p>Secure access, important updates, and quick assistance stay within a focused Laravel prototype.</p>
        </div>

        <section class="card hub-card glass-card">
            <header class="card-header">
                <div class="brand-row">
                    <span class="brand-mark" aria-hidden="true">KAH</span>
                    <span class="brand">Kaizen App Hub</span>
                </div>

                <h1>Welcome to Kaizen App Hub</h1>
                <p class="muted">
                    Access verification, mailbox, and AI assistant tools from one dashboard.
                </p>
            </header>

            <div class="actions-grid">
                <a class="btn primary" href="{{ route('login') }}">Sign in</a>
                <a class="btn light" href="{{ route('register') }}">Create account</a>
            </div>

            <hr>

            <div class="action-stack">
                <a class="btn light" href="{{ route('otp.phone') }}">Send OTP via SMS</a>
                <a class="btn light" href="{{ route('otp.email') }}">Send OTP via Email</a>
                <a class="btn light" href="{{ route('otp.verify') }}">Validate OTP</a>
                <a class="btn light" href="{{ route('mailbox') }}">Open Mailbox</a>
                <a class="btn light" href="{{ route('ai-chatbot') }}">AI Chatbot</a>
            </div>

            <button class="btn google" type="button" onclick="loginWithGoogle()">
                <img src="{{ asset('assets/Google_Favicon_2025.svg.webp') }}" alt="" height="26" width="26">
                Login with Google Account
            </button>

            <p class="note">
                UI/UX prototype only. Authentication, OTP, mailbox, and chatbot flows are simulated for the next development stage.
            </p>
        </section>
    </section>
</main>
@endsection
