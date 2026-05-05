@extends('layouts.app')

@section('title', 'RepoHive App Hub')
@section('bodyClass', 'auth-surface')

@section('content')
<div class="center-screen">
    <main class="card hub-card">
        <div class="brand">RepoHive App Hub</div>

        <h1>Welcome to RepoHive</h1>
        <p class="muted">
            Access verification, mailbox, and AI assistant tools from one Laravel prototype.
        </p>

        <div class="actions-grid">
            <a class="btn primary" href="{{ route('login') }}">Sign in</a>
            <a class="btn light" href="{{ route('register') }}">Create account</a>
        </div>

        <hr>

        <a class="btn light" href="{{ route('otp.phone') }}">Send OTP via SMS</a>
        <a class="btn light" href="{{ route('otp.email') }}">Send OTP via Email</a>
        <a class="btn light" href="{{ route('otp.verify') }}">Validate OTP</a>
        <a class="btn light" href="{{ route('mailbox') }}">Open Mailbox</a>
        <a class="btn light" href="{{ route('ai-chatbot') }}">AI Chatbot</a>

        <button class="btn google" type="button" onclick="loginWithGoogle()">
            <img src="{{ asset('assets/Google_Favicon_2025.svg.webp') }}" alt="" height="26" width="26">
            Login with Google Account
        </button>

        <p class="note">
            UI/UX prototype only. Authentication, OTP, mailbox, and chatbot flows are simulated for the next development stage.
        </p>
    </main>
</div>
@endsection
