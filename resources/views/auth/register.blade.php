@extends('layouts.app')

@section('title', 'RepoHive Register')
@section('bodyClass', 'auth-surface')

@section('content')
<div class="center-screen">
    <main class="card">
        <div class="brand">RepoHive Registration</div>
        <h1>Create account</h1>
        <p class="muted">Register a prototype account and continue to OTP verification.</p>

        <form onsubmit="event.preventDefault(); registerAccount();">
            <label for="registerName">Full Name</label>
            <input id="registerName" type="text" placeholder="Student User" autocomplete="name">

            <label for="registerEmail">Email Address</label>
            <input id="registerEmail" type="email" placeholder="student@example.com" autocomplete="email">

            <label for="registerPassword">Password</label>
            <input id="registerPassword" type="password" placeholder="Create password" autocomplete="new-password">

            <button class="btn primary" type="submit">Create account</button>
        </form>

        <a class="link" href="{{ route('login') }}">Already have an account</a>
        <a class="link subtle-link" href="{{ route('home') }}">Back to hub</a>
    </main>
</div>
@endsection
