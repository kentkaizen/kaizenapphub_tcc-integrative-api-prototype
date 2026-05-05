@extends('layouts.app')

@section('title', 'Send Email OTP')
@section('bodyClass', 'auth-surface')

@section('content')
<div class="center-screen">
    <main class="card">
        <div class="brand">Email Verification</div>
        <h1>Send OTP to Email</h1>
        <p class="muted">Enter your email address to receive a 6-digit verification code.</p>

        <form onsubmit="event.preventDefault(); sendEmailOtp();">
            <label for="email">Email Address</label>
            <input id="email" type="email" placeholder="example@company.com" autocomplete="email">

            <button class="btn primary" type="submit">Send OTP</button>
        </form>

        <a class="link" href="{{ route('otp.phone') }}">Use phone instead</a>
        <a class="link subtle-link" href="{{ route('home') }}">Back to hub</a>
    </main>
</div>
@endsection
