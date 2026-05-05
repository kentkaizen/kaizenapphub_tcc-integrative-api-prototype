@extends('layouts.app')

@section('title', 'Validate OTP')
@section('bodyClass', 'auth-surface')

@section('content')
<div class="center-screen">
    <main class="card">
        <div class="brand">OTP Verification</div>
        <h1>Validate OTP</h1>
        <p class="muted">
            Code sent to: <strong id="otpTarget">your account</strong>
        </p>

        <div class="success">Prototype OTP: <strong>123456</strong></div>

        <form onsubmit="event.preventDefault(); validateOtp();">
            <div class="otp-box" aria-label="One-time password">
                <input maxlength="1" class="otp" inputmode="numeric" aria-label="OTP digit 1">
                <input maxlength="1" class="otp" inputmode="numeric" aria-label="OTP digit 2">
                <input maxlength="1" class="otp" inputmode="numeric" aria-label="OTP digit 3">
                <input maxlength="1" class="otp" inputmode="numeric" aria-label="OTP digit 4">
                <input maxlength="1" class="otp" inputmode="numeric" aria-label="OTP digit 5">
                <input maxlength="1" class="otp" inputmode="numeric" aria-label="OTP digit 6">
            </div>

            <button class="btn primary" type="submit">Verify OTP</button>
        </form>

        <p id="message" class="muted center"></p>
        <a class="link subtle-link" href="{{ route('home') }}">Back to hub</a>
    </main>
</div>
@endsection
