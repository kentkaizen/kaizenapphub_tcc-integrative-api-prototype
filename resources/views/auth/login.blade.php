@extends('layouts.app')

@section('title', 'Kaizen App Hub Login')
@section('bodyClass', 'auth-surface')

@section('content')
<main class="app-shell">
    <section class="auth-view auth-view-compact">
        <div class="auth-intro">
            <span class="brand-chip">Kaizen App Hub Access</span>
            <h1>Sign in to your verified workspace.</h1>
            <p>Use the same prototype login flow, refreshed with the dark glass dashboard style.</p>
        </div>

        <section class="card glass-card">
            <header class="card-header">
                <div class="brand-row">
                    <span class="brand-mark" aria-hidden="true">KAH</span>
                    <span class="brand">Kaizen App Hub Authentication</span>
                </div>
                <h1>Sign in</h1>
                <p class="muted">Use a prototype account to continue to the mailbox dashboard.</p>
            </header>

            @if ($errors->any())
                <div class="auth-alert" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <label for="loginEmail">Email Address</label>
                <input id="loginEmail" name="email" type="email" value="{{ old('email') }}" placeholder="student@example.com" autocomplete="email" required>

                <label for="loginPassword">Password</label>
                <input id="loginPassword" name="password" type="password" placeholder="Enter password" autocomplete="current-password" required>

                <button class="btn primary" type="submit">Sign in</button>
            </form>

            <button class="btn google" type="button" onclick="loginWithGoogle()">
                <img src="{{ asset('assets/Google_Favicon_2025.svg.webp') }}" alt="" height="26" width="26">
                Login with Google Account
            </button>

            <a class="link" href="{{ route('register') }}">Create an account</a>
            <a class="link subtle-link" href="{{ route('home') }}">Back to hub</a>
        </section>
    </section>
</main>
@endsection
