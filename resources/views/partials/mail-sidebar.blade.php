<aside class="sidebar side-nav">
    <a class="side-brand" href="{{ route('home') }}">
        <span class="brand-mark" aria-hidden="true">KAH</span>
        <span>
            <strong>Kaizen App Hub</strong>
            <small>Verified session</small>
        </span>
    </a>

    <button class="compose-btn" type="button" onclick="openCompose()">+ Compose</button>

    <nav class="nav-list">
        <button class="menu active" type="button" data-box="inbox" onclick="showInbox()">
            <span>Inbox</span>
            <span>3</span>
        </button>
        <button class="menu" type="button" data-box="sent" onclick="showSent()">
            <span>Sent</span>
            <span id="sentCount">0</span>
        </button>
        <button class="menu" type="button" data-box="drafts" onclick="showDrafts()">
            <span>Drafts</span>
            <span>0</span>
        </button>
        <button class="menu" type="button" data-box="archived" onclick="showArchived()">
            <span>Archived</span>
            <span>4</span>
        </button>
    </nav>

    <a class="menu link-menu" href="{{ route('ai-chatbot') }}">
        <span>AI Chatbot</span>
        <span>Open</span>
    </a>

    <form method="POST" action="{{ route('logout') }}" class="logout-form">
        @csrf
        <button class="menu logout-menu" type="submit">
            <span>Log out</span>
            <span>{{ auth()->user()->name }}</span>
        </button>
    </form>
</aside>
