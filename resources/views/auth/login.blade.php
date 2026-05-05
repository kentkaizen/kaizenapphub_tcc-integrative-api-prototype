@extends('layouts.app')

@section('title', 'RepoHive Login')
@section('bodyClass', 'auth-surface')

@section('content')
<div class="center-screen">
    <main class="card">
        <div class="brand">RepoHive Authentication</div>
        <h1>Sign in</h1>
        <p class="muted">Use a prototype account to continue to the mailbox dashboard.</p>

        <form onsubmit="event.preventDefault(); loginWithEmail();">
            <label for="loginEmail">Email Address</label>
            <input id="loginEmail" type="email" placeholder="student@example.com" autocomplete="email">

            <label for="loginPassword">Password</label>
            <input id="loginPassword" type="password" placeholder="Enter password" autocomplete="current-password">

            <button class="btn primary" type="submit">Sign in</button>
        </form>

        <button class="btn google" type="button" onclick="loginWithGoogle()">
            <img src="{{ asset('assets/Google_Favicon_2025.svg.webp') }}" alt="" height="26" width="26">
            Login with Google Account
        </button>

        <a class="link" href="{{ route('register') }}">Create an account</a>
        <a class="link subtle-link" href="{{ route('home') }}">Back to hub</a>
    </main>
</div>
@endsection
