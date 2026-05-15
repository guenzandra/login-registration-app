<!--resources/views/admin/nav.blade.php-->
<div class="sidebar-logo-wrap">
  <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
    <svg class="logo-icon" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
      <rect width="34" height="34" rx="9" fill="#4f7fff" fill-opacity="0.15" />
      <path d="M9 17C9 12.582 12.582 9 17 9s8 3.582 8 8-3.582 8-8 8"
        stroke="#4f7fff" stroke-width="2" stroke-linecap="round" />
      <path d="M17 14v3l2 2" stroke="#4f7fff" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round" />
      <circle cx="17" cy="25" r="1.2" fill="#4f7fff" />
      <path d="M21 21.5l3.5 3.5" stroke="#4f7fff" stroke-width="1.8" stroke-linecap="round" />
    </svg>
    <span class="logo-text">Guen<span>Hub</span></span>
  </a>
</div>

<nav class="sidebar-nav" aria-label="Main navigation">

  <div class="nav-group-label">Main</div>

  <a href="{{ route('admin.dashboard') }}" class="nav-link">
    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.7"
      stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <rect x="3" y="3" width="7" height="7" rx="1.5" />
      <rect x="14" y="3" width="7" height="7" rx="1.5" />
      <rect x="14" y="14" width="7" height="7" rx="1.5" />
      <rect x="3" y="14" width="7" height="7" rx="1.5" />
    </svg>
    Dashboard
  </a>

  <a href="{{ route('admin.accounts') }}" class="nav-link">
    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.7"
      stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <circle cx="9" cy="7" r="4" />
      <path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" />
      <path d="M16 3.13a4 4 0 0 1 0 7.75" />
      <path d="M21 21v-2a4 4 0 0 0-3-3.87" />
    </svg>
    User Accounts
  </a>

  <div class="nav-group-label" style="margin-top:14px;">System</div>

  <!-- <a href="{{ route('admin.settings') }}" class="nav-link">
    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.7"
      stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <circle cx="12" cy="12" r="3" />
      <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33
               1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33
               l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4
               h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0
               9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33
               l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4
               h-.09a1.65 1.65 0 0 0-1.51 1z" />
    </svg>
    Settings
  </a> -->

</nav>

<div class="sidebar-footer">
  <a href="{{ route('logout') }}" class="nav-link">
    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.7"
      stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
      <polyline points="16 17 21 12 16 7" />
      <line x1="21" y1="12" x2="9" y2="12" />
    </svg>
    Logout
  </a>
</div>

<div class="topbar-time">
  <span class="flag">🇵🇭</span>
  <span id="time"></span>
</div>
<div class="topbar-center"><span id="date"></span></div>