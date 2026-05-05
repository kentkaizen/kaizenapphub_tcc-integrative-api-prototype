<aside class="sidebar">
    <a class="brand white" href="{{ route('home') }}">RepoHive</a>

    <button class="compose-btn" type="button" onclick="openCompose()">+ Compose</button>

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

    <a class="menu link-menu" href="{{ route('ai-chatbot') }}">
        <span>AI Chatbot</span>
        <span>Open</span>
    </a>
</aside>
