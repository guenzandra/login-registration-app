<style>
 .sidebar {
    width: 100%;
    height: 100%;
    background: var(--surface);
    border-right: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    position: relative;
  }

  /* top gold glow */
  .sidebar::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 220px;
    background: radial-gradient(ellipse 100% 100% at 50% 0%, rgba(201, 168, 76, .1) 0%, transparent 70%);
    pointer-events: none;
  }

  /* noise */
  .sidebar::after {
    content: '';
    position: absolute;
    inset: 0;
    opacity: .025;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
    pointer-events: none;
  }

  /* ── Brand ── */
  .sb-brand {
    position: relative;
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: 1.6rem 1.4rem 1.4rem;
    border-bottom: 1px solid var(--border);
    flex-shrink: 0;
  }

  .sb-mark {
    width: 36px;
    height: 36px;
    background: var(--gold-dim);
    border: 1px solid var(--gold-border);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .sb-name {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text);
  }

  .sb-role {
    font-size: .62rem;
    letter-spacing: .18em;
    text-transform: uppercase;
    color: var(--gold);
    font-weight: 600;
    margin-top: .05rem;
  }

  /* ── User card ── */
  .sb-user {
    position: relative;
    margin: 1rem 1rem .5rem;
    padding: .9rem 1rem;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: .75rem;
    flex-shrink: 0;
  }

  .sb-avatar {
    width: 36px;
    height: 36px;
    border-radius: 9px;
    background: linear-gradient(135deg, rgba(201, 168, 76, .3), rgba(201, 168, 76, .05));
    border: 1px solid var(--gold-border);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.05rem;
    font-weight: 600;
    color: var(--gold);
  }

  .sb-uname {
    font-size: .84rem;
    font-weight: 600;
    color: var(--text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .sb-badge {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    font-size: .62rem;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--gold);
    font-weight: 600;
    margin-top: .15rem;
  }

  .sb-dot {
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: var(--gold);
    animation: sb-pulse 2.2s ease-in-out infinite;
  }

  @keyframes sb-pulse {

    0%,
    100% {
      opacity: 1;
      transform: scale(1);
    }

    50% {
      opacity: .4;
      transform: scale(.7);
    }
  }

  /* ── Section label ── */
  .sb-section {
    position: relative;
    padding: .85rem 1.4rem .4rem;
    font-size: .62rem;
    letter-spacing: .2em;
    text-transform: uppercase;
    color: var(--muted);
    font-weight: 600;
    flex-shrink: 0;
  }

  /* ── Nav list ── */
  .sb-list {
    position: relative;
    list-style: none;
    padding: .25rem .75rem;
    display: flex;
    flex-direction: column;
    gap: .15rem;
    flex: 1;
    overflow-y: auto;
    scrollbar-width: none;
  }

  .sb-list::-webkit-scrollbar {
    display: none;
  }

  /* ── Nav item ── */
  .sb-item a {
    display: flex;
    align-items: center;
    gap: .75rem;
    width: 100%;
    padding: .72rem .85rem;
    border-radius: 8px;
    border: 1px solid transparent;
    font-family: 'Syne', sans-serif;
    font-size: .84rem;
    font-weight: 500;
    color: var(--muted);
    text-decoration: none;
    transition: all .2s ease;
    position: relative;
  }

  .sb-item a:hover {
    color: var(--text);
    background: rgba(255, 255, 255, .04);
  }

  .sb-item.active a {
    color: var(--gold);
    background: var(--gold-dim);
    border-color: var(--gold-border);
  }

  .sb-item.active a::before {
    content: '';
    position: absolute;
    left: -2px;
    top: 20%;
    bottom: 20%;
    width: 3px;
    background: var(--gold);
    border-radius: 0 3px 3px 0;
  }

  .sb-icon {
    width: 32px;
    height: 32px;
    border-radius: 7px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: rgba(255, 255, 255, .04);
    transition: background .2s;
  }

  .sb-item.active .sb-icon {
    background: rgba(201, 168, 76, .15);
    color: var(--gold);
  }

  .sb-item a:hover .sb-icon {
    background: rgba(255, 255, 255, .07);
  }

  .sb-label {
    flex: 1;
  }

  .sb-chip {
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .06em;
    background: var(--gold-dim);
    color: var(--gold);
    border: 1px solid var(--gold-border);
    border-radius: 100px;
    padding: .15rem .5rem;
  }

  /* ── Divider ── */
  .sb-divider {
    height: 1px;
    background: var(--border);
    margin: .5rem .75rem;
    flex-shrink: 0;
  }

  /* ── Logout ── */
  .sb-logout-wrap {
    position: relative;
    padding: .75rem .75rem 1.2rem;
    flex-shrink: 0;
    border-top: 1px solid var(--border);
  }

  .sb-logout {
    display: flex;
    align-items: center;
    gap: .75rem;
    width: 100%;
    padding: .75rem .85rem;
    border-radius: 8px;
    border: 1px solid transparent;
    font-family: 'Syne', sans-serif;
    font-size: .84rem;
    font-weight: 600;
    color: var(--danger);
    cursor: pointer;
    background: transparent;
    text-decoration: none;
    transition: all .2s ease;
  }

  .sb-logout:hover {
    background: var(--danger-dim);
    border-color: rgba(217, 95, 95, .2);
  }

  .sb-logout-icon {
    width: 32px;
    height: 32px;
    border-radius: 7px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--danger-dim);
    flex-shrink: 0;
    transition: background .2s;
  }

  .sb-logout:hover .sb-logout-icon {
    background: rgba(217, 95, 95, .18);
  }
</style>

<nav class="sidebar" aria-label="Admin navigation">

  {{-- Brand --}}
  <div class="sb-brand">
    <div class="sb-mark">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#c9a84c" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <polygon points="12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5 12 2" />
        <line x1="12" y1="2" x2="12" y2="22" />
        <polyline points="2 8.5 12 13 22 8.5" />
      </svg>
    </div>
    <div>
      <div class="sb-name">Guen</div>
      <div class="sb-role">Admin Panel</div>
    </div>
  </div>

  {{-- User card --}}
  <div class="sb-user">
    <div class="sb-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
    <div style="overflow:hidden">
      <div class="sb-uname">{{ auth()->user()->name ?? 'Administrator' }}</div>
      <div class="sb-badge">
        <span class="sb-dot"></span>
        Online
      </div>
    </div>
  </div>

  {{-- Section label --}}
  <div class="sb-section">Main Menu</div>

  {{-- Nav links --}}
  <ul class="sb-list" role="list">

    {{-- Dashboard --}}
    <li class="sb-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <a href="{{ route('dashboard') }}" {{ request()->routeIs('dashboard') ? 'aria-current=page' : '' }}>
        <span class="sb-icon">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="7" height="7" rx="1" />
            <rect x="14" y="3" width="7" height="7" rx="1" />
            <rect x="3" y="14" width="7" height="7" rx="1" />
            <rect x="14" y="14" width="7" height="7" rx="1" />
          </svg>
        </span>
        <span class="sb-label">Dashboard</span>
      </a>
    </li>

    {{-- Registered Accounts --}}
    <li class="sb-item {{ request()->routeIs('accounts*') ? 'active' : '' }}">
      <a href="{{ route('accounts') }}" {{ request()->routeIs('accounts*') ? 'aria-current=page' : '' }}>
        <span class="sb-icon">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
          </svg>
        </span>
        <span class="sb-label">Registered Accounts</span>
        {{-- Optional: pass $userCount from controller --}}
        @isset($userCount)
        <span class="sb-chip">{{ $userCount }}</span>
        @endisset
      </a>
    </li>

    {{-- Create Account --}}
    <li class="sb-item {{ request()->routeIs('account.create') ? 'active' : '' }}">
      <a href="{{ route('account.create') }}" {{ request()->routeIs('account.create') ? 'aria-current=page' : '' }}>
        <span class="sb-icon">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
            <circle cx="8.5" cy="7" r="4" />
            <line x1="20" y1="8" x2="20" y2="14" />
            <line x1="23" y1="11" x2="17" y2="11" />
          </svg>
        </span>
        <span class="sb-label">Create Account</span>
      </a>
    </li>

    <li role="separator">
      <div class="sb-divider"></div>
    </li>

    {{-- Settings --}}
    <li class="sb-item {{ request()->routeIs('settings*') ? 'active' : '' }}">
      <a href="{{ route('settings') }}" {{ request()->routeIs('settings*') ? 'aria-current=page' : '' }}>
        <span class="sb-icon">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="3" />
            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
          </svg>
        </span>
        <span class="sb-label">Settings</span>
      </a>
    </li>

  </ul>

  {{-- Sign Out --}}
  <div class="sb-logout-wrap">
    <form method="POST" action="{{ route('logout') }}" id="logout-form">
      @csrf
      <a href="#" class="sb-logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <span class="sb-logout-icon">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
            <polyline points="16 17 21 12 16 7" />
            <line x1="21" y1="12" x2="9" y2="12" />
          </svg>
        </span>
        Sign Out
      </a>
    </form>
  </div>

</nav>