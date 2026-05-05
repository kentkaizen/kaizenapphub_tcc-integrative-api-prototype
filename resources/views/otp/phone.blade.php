@extends('layouts.app')

@section('title', 'Send Phone OTP')
@section('bodyClass', 'auth-surface')

@section('content')
<div class="center-screen">
    <main class="card">
        <div class="brand">Phone Verification</div>
        <h1>Send OTP to Phone</h1>
        <p class="muted">Enter your phone number to receive a 6-digit verification code.</p>

        <form onsubmit="event.preventDefault(); sendPhoneOtp();">
            <label for="phone">Phone Number</label>
            <input id="phone" type="tel" placeholder="+63 900 000 0000" autocomplete="tel">

            <button class="btn primary" type="submit">Send OTP</button>
        </form>

        <a class="link" href="{{ route('otp.email') }}">Use email instead</a>
        <a class="link subtle-link" href="{{ route('home') }}">Back to hub</a>
    </main>
</div>
@endsection
