<nav class="topbar">
    <a class="topbar-brand" href="{{ route('app') }}">
        <div class="topbar-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                <polyline points="14 2 14 8 20 8" />
                <line x1="16" y1="13" x2="8" y2="13" />
                <line x1="16" y1="17" x2="8" y2="17" />
            </svg>
        </div>
        <span>Etsy Order Extract</span>
    </a>
    <div class="topbar-right">
        <a href="{{ route('history') }}" class="topbar-support-btn {{ request()->routeIs('history') ? 'active' : '' }}">
            <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
            </svg>
            Lịch sử
        </a>
        <a href="{{ route('support') }}" class="topbar-support-btn {{ request()->routeIs('support') ? 'active' : '' }}">
            <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14">
                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
            </svg>
            Ủng hộ
        </a>
        <div class="topbar-badge">v1.0.0</div>
    </div>
</nav>
