@extends('layouts.app')

@section('title', 'Kaizen App Hub Mailbox')
@section('bodyClass', 'dashboard-surface')

@section('content')
<div class="mailbox app-shell dashboard-view">
    @include('partials.mail-sidebar')

    <main class="main workspace">
        <header class="topbar">
            <div>
                <p class="welcome-text">Kaizen App Hub Mailbox</p>
                <span class="status-dot">Verified</span>
                <h2 id="mailTitle">Inbox</h2>
                <small id="userEmail">{{ auth()->user()->email }}</small>
            </div>
            <div class="topbar-actions">
                <input id="searchMail" placeholder="Search mail..." onkeyup="filterMail()">
                <button class="btn compact primary" type="button" onclick="openCompose()">Compose</button>
                <a class="btn compact light" href="{{ route('ai-chatbot') }}">AI Chatbot</a>
            </div>
        </header>

        <section class="mail-area">
            <div id="mailList" class="mail-list"></div>

            <article class="preview">
                <h2 id="previewTitle">Select an email</h2>
                <p id="previewMeta" class="muted"></p>
                <p id="previewBody"></p>
            </article>
        </section>
    </main>
</div>

<div id="composeModal" class="modal" aria-hidden="true">
    <div class="modal-card glass-card">
        <button class="close" type="button" onclick="closeCompose()" aria-label="Close compose modal">x</button>
        <h2>Compose Email</h2>

        <form onsubmit="event.preventDefault(); sendEmail();">
            <label for="composeTo">To</label>
            <input id="composeTo" type="email" placeholder="recipient@email.com">

            <label for="composeSubject">Subject</label>
            <input id="composeSubject" type="text" placeholder="Email subject">

            <label for="composeBody">Message</label>
            <textarea id="composeBody" placeholder="Write your message..."></textarea>

            <button class="btn primary" type="submit">Send Email</button>
        </form>
    </div>
</div>
@endsection
