@extends('layouts.app')

@section('title', 'Kaizen App Hub Register')
@section('bodyClass', 'auth-surface')

@section('content')
<main class="app-shell">
    <section class="auth-view auth-view-compact">
        <div class="auth-intro">
            <span class="brand-chip">Kaizen App Hub Access</span>
            <h1>Create a secure prototype account.</h1>
            <p>Registration still moves into the existing OTP verification flow after the form is submitted.</p>
        </div>

        <section class="card glass-card">
            <header class="card-header">
                <div class="brand-row">
                    <span class="brand-mark" aria-hidden="true">KAH</span>
                    <span class="brand">Kaizen App Hub Registration</span>
                </div>
                <h1>Create account</h1>
                <p class="muted">Register a prototype account and continue to OTP verification.</p>
            </header>

            @if ($errors->any())
                <div class="auth-alert" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}">
                @csrf

                <label for="registerName">Full Name</label>
                <input id="registerName" name="name" type="text" value="{{ old('name') }}" placeholder="Student User" autocomplete="name" required>

                <label for="registerEmail">Email Address</label>
                <input id="registerEmail" name="email" type="email" value="{{ old('email') }}" placeholder="student@example.com" autocomplete="email" required>

                <label for="registerPhone">Phone Number</label>
                <input id="registerPhone" name="phone" type="tel" value="{{ old('phone') }}" placeholder="+63 900 000 0000" autocomplete="tel" required>

                <label for="registerPassword">Password</label>
                <input id="registerPassword" name="password" type="password" placeholder="Create password" autocomplete="new-password" required>

                <button class="btn primary" type="submit">Create account</button>
            </form>

            <a class="link" href="{{ route('login') }}">Already have an account</a>
            <a class="link subtle-link" href="{{ route('home') }}">Back to hub</a>
        </section>
    </section>
</main>
@endsection
