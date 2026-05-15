{{-- resources/views/admin/layout.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>GuenHub Admin — {{ config('app.name') }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Arial:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    *,
    *::before,
    *::after {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --sidebar-w: 256px;
      --topbar-h: 60px;
      --bg: #0b0f18;
      --surface: #111827;
      --surface2: #1a2235;
      --border: rgba(255, 255, 255, 0.07);
      --border2: rgba(255, 255, 255, 0.12);
      --text: #f0f4ff;
      --muted: #8b97b1;
      --accent: #4f7fff;
      --accent-dim: rgba(79, 127, 255, 0.12);
      --accent-glow: rgba(79, 127, 255, 0.25);
      --danger: #ff5c5c;
      --success: #34d399;
      --warn: #fbbf24;
      --radius: 10px;
      --radius-lg: 16px;
      --font: 'Arial', sans-serif;
      --shadow: 0 4px 24px rgba(0, 0, 0, 0.4);
    }

    html,
    body {
      height: 100%;
      font-family: var(--font);
      background: var(--bg);
      color: var(--text);
      overflow-x: hidden;
    }

    /* ─── SIDEBAR ─── */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: var(--sidebar-w);
      height: 100vh;
      background: var(--surface);
      border-right: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      z-index: 1000;
      transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .sidebar-logo {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 22px 20px 18px;
      border-bottom: 1px solid var(--border);
      text-decoration: none;
    }

    .logo-icon {
      width: 34px;
      height: 34px;
      flex-shrink: 0;
    }

    .logo-text {
      font-size: 1.15rem;
      font-weight: 700;
      letter-spacing: -0.02em;
      color: var(--text);
    }

    .logo-text span {
      color: var(--accent);
    }

    .sidebar-nav {
      flex: 1;
      padding: 16px 12px;
      overflow-y: auto;
    }

    .nav-group-label {
      font-size: 0.65rem;
      font-weight: 600;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: var(--muted);
      padding: 10px 10px 6px;
    }

    .nav-link {
      display: flex;
      align-items: center;
      gap: 11px;
      padding: 10px 12px;
      color: var(--muted);
      text-decoration: none;
      border-radius: var(--radius);
      font-size: 0.88rem;
      font-weight: 500;
      transition: all 0.2s ease;
      position: relative;
      margin-bottom: 2px;
    }

    .nav-link svg {
      width: 18px;
      height: 18px;
      flex-shrink: 0;
      stroke: currentColor;
    }

    .nav-link:hover {
      background: var(--accent-dim);
      color: var(--text);
    }

    .nav-link.active {
      background: var(--accent-dim);
      color: var(--accent);
    }

    .nav-link.active::before {
      content: '';
      position: absolute;
      left: 0;
      top: 6px;
      bottom: 6px;
      width: 3px;
      border-radius: 0 3px 3px 0;
      background: var(--accent);
    }

    .nav-link .badge {
      margin-left: auto;
      font-size: 0.7rem;
      background: var(--accent);
      color: #fff;
      padding: 2px 7px;
      border-radius: 20px;
    }

    .sidebar-footer {
      padding: 14px 12px;
      border-top: 1px solid var(--border);
    }

    .sidebar-footer .nav-link {
      color: var(--muted);
    }

    .sidebar-footer .nav-link:hover {
      color: var(--danger);
      background: rgba(255, 92, 92, 0.08);
    }

    /* ─── TOPBAR ─── */
    .topbar {
      position: fixed;
      top: 0;
      left: var(--sidebar-w);
      right: 0;
      height: var(--topbar-h);
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 24px;
      z-index: 900;
      transition: left 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .topbar-left {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .menu-btn {
      display: none;
      background: none;
      border: none;
      color: var(--muted);
      cursor: pointer;
      padding: 6px;
      border-radius: 8px;
      line-height: 0;
      transition: color 0.2s, background 0.2s;
    }

    .menu-btn:hover {
      color: var(--text);
      background: var(--surface2);
    }

    .topbar-time {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.82rem;
      color: var(--muted);
    }

    .topbar-time .flag {
      font-size: 1rem;
      line-height: 1;
    }

    .topbar-time #time {
      color: var(--accent);
      font-weight: 600;
      font-size: 0.88rem;
      letter-spacing: 0.02em;
    }

    .topbar-center {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      font-size: 0.8rem;
      color: var(--muted);
    }

    .topbar-right {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--accent), #8b5cf6);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.75rem;
      font-weight: 700;
      color: #fff;
      cursor: pointer;
    }

    .topbar-icon-btn {
      background: none;
      border: none;
      color: var(--muted);
      cursor: pointer;
      padding: 6px;
      border-radius: 8px;
      line-height: 0;
      position: relative;
      transition: color 0.2s, background 0.2s;
    }

    .topbar-icon-btn:hover {
      color: var(--text);
      background: var(--surface2);
    }

    .topbar-icon-btn .dot {
      position: absolute;
      top: 4px;
      right: 4px;
      width: 7px;
      height: 7px;
      background: var(--accent);
      border-radius: 50%;
      border: 2px solid var(--surface);
    }

    /* ─── MAIN CONTENT ─── */
    .main {
      margin-left: var(--sidebar-w);
      margin-top: var(--topbar-h);
      min-height: calc(100vh - var(--topbar-h));
      padding: 28px 28px 40px;
    }

    /* ─── TOAST SYSTEM ─── */
    #toast-wrap {
      position: fixed;
      bottom: 24px;
      right: 24px;
      z-index: 9999;
      display: flex;
      flex-direction: column;
      gap: 10px;
      pointer-events: none;
    }

    .toast {
      display: flex;
      align-items: center;
      gap: 12px;
      background: var(--surface2);
      border: 1px solid var(--border2);
      border-radius: var(--radius);
      padding: 12px 16px;
      min-width: 240px;
      max-width: 340px;
      box-shadow: var(--shadow);
      pointer-events: all;
      animation: toastIn 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    .toast.out {
      animation: toastOut 0.3s ease forwards;
    }

    .toast-icon {
      flex-shrink: 0;
    }

    .toast-body {
      flex: 1;
    }

    .toast-title {
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--text);
    }

    .toast-msg {
      font-size: 0.78rem;
      color: var(--muted);
      margin-top: 2px;
    }

    .toast-close {
      background: none;
      border: none;
      color: var(--muted);
      cursor: pointer;
      padding: 2px;
      line-height: 0;
      flex-shrink: 0;
    }

    .toast-close:hover {
      color: var(--text);
    }

    .toast.success .toast-icon {
      color: var(--success);
    }

    .toast.error .toast-icon {
      color: var(--danger);
    }

    .toast.info .toast-icon {
      color: var(--accent);
    }

    .toast.warn .toast-icon {
      color: var(--warn);
    }

    @keyframes toastIn {
      from {
        opacity: 0;
        transform: translateX(24px) scale(0.96);
      }

      to {
        opacity: 1;
        transform: none;
      }
    }

    @keyframes toastOut {
      from {
        opacity: 1;
        transform: none;
      }

      to {
        opacity: 0;
        transform: translateX(24px);
      }
    }

    /* ─── OVERLAY (mobile) ─── */
    .sidebar-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.6);
      z-index: 999;
      opacity: 0;
      transition: opacity 0.3s;
    }

    .sidebar-overlay.show {
      opacity: 1;
    }

    /* ─── MOBILE ─── */
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }

      .sidebar.open {
        transform: translateX(0);
        box-shadow: 0 0 40px rgba(0, 0, 0, 0.6);
      }

      .sidebar-overlay {
        display: block;
      }

      .topbar {
        left: 0 !important;
      }

      .main {
        margin-left: 0;
      }

      .menu-btn {
        display: flex;
        align-items: center;
      }

      .topbar-center {
        display: none;
      }
    }

    /* ─── PAGE ENTER ANIMATION ─── */
    .main>* {
      animation: fadeUp 0.4s ease both;
    }

    .main>*:nth-child(1) {
      animation-delay: 0.05s;
    }

    .main>*:nth-child(2) {
      animation-delay: 0.12s;
    }

    .main>*:nth-child(3) {
      animation-delay: 0.19s;
    }

    @keyframes fadeUp {
      from {
        opacity: 0;
        transform: translateY(14px);
      }

      to {
        opacity: 1;
        transform: none;
      }
    }

    /* ─── SCROLLBAR ─── */
    ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }

    ::-webkit-scrollbar-track {
      background: transparent;
    }

    ::-webkit-scrollbar-thumb {
      background: var(--border2);
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: var(--accent);
    }
  </style>
</head>

<body>

  {{-- Toast container --}}
  <div id="toast-wrap" role="status" aria-live="polite"></div>

  {{-- Sidebar overlay (mobile) --}}
  <div class="sidebar-overlay" id="overlay"></div>

  {{-- Sidebar --}}
  <aside class="sidebar" id="sidebar" role="navigation" aria-label="Main navigation">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
      <svg class="logo-icon" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect width="34" height="34" rx="9" fill="#4f7fff" fill-opacity="0.15" />
        <path d="M9 17C9 12.582 12.582 9 17 9s8 3.582 8 8-3.582 8-8 8" stroke="#4f7fff" stroke-width="2" stroke-linecap="round" />
        <path d="M17 14v3l2 2" stroke="#4f7fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        <circle cx="17" cy="25" r="1.2" fill="#4f7fff" />
        <path d="M21 21.5l3.5 3.5" stroke="#4f7fff" stroke-width="1.8" stroke-linecap="round" />
      </svg>
      <span class="logo-text">Guen<span>Hub</span></span>
    </a>

    <nav class="sidebar-nav">
      <div class="nav-group-label">Main</div>

      <a href="{{ route('admin.dashboard') }}" class="nav-link">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="7" height="7" rx="1.5" />
          <rect x="14" y="3" width="7" height="7" rx="1.5" />
          <rect x="14" y="14" width="7" height="7" rx="1.5" />
          <rect x="3" y="14" width="7" height="7" rx="1.5" />
        </svg>
        Dashboard
      </a>

      <a href="{{ route('admin.accounts') }}" class="nav-link">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="9" cy="7" r="4" />
          <path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" />
          <path d="M16 3.13a4 4 0 0 1 0 7.75" />
          <path d="M21 21v-2a4 4 0 0 0-3-3.87" />
        </svg>
        User Accounts
      </a>

      <!-- <div class="nav-group-label" style="margin-top:14px;">System</div> -->

      <!-- <a href="{{ route('admin.settings') }}" class="nav-link">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="3" />
          <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
        </svg>
        Settings
      </a> -->
    </nav>

    <div class="sidebar-footer">
      <form method="POST" action="{{ route('logout') }}" style="display: inline;">
        @csrf
        <button type="submit" class="logout-btn" style="background: none; border: none; cursor: pointer; width: 100%; text-align: left; padding: 8px 12px; color: inherit;">
          <i class="fas fa-sign-out-alt"></i> Logout
        </button>
      </form>
    </div>
  </aside>

  {{-- Topbar --}}
  <header class="topbar" id="topbar">
    <div class="topbar-left">
      <button class="menu-btn" id="menu-btn" aria-label="Toggle menu" aria-expanded="false">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
          <line x1="3" y1="6" x2="21" y2="6" />
          <line x1="3" y1="12" x2="21" y2="12" />
          <line x1="3" y1="18" x2="21" y2="18" />
        </svg>
      </button>
      <div class="topbar-time">
        <span class="flag">🇵🇭</span>
        <span id="time" aria-live="off"></span>
      </div>
    </div>

    <div class="topbar-center">
      <span id="date"></span>
    </div>

    <div class="topbar-right">
      <button class="topbar-icon-btn" aria-label="Notifications" onclick="showToast('info','Notifications','No new notifications.')">
        <span class="dot"></span>
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
          <path d="M13.73 21a2 2 0 0 1-3.46 0" />
        </svg>
      </button>
      <div class="avatar" title="Admin">A</div>
    </div>
  </header>

  {{-- Page content --}}
  <main class="main" id="main-content">
    @yield('content')
  </main>

  <script>
    // ─── TIME / DATE ───
    function updateClock() {
      const ph = {
        timeZone: 'Asia/Manila'
      };
      document.getElementById('time').textContent =
        new Date().toLocaleTimeString('en-PH', {
          ...ph,
          hour12: true,
          hour: '2-digit',
          minute: '2-digit',
          second: '2-digit'
        });
      document.getElementById('date').textContent =
        new Date().toLocaleDateString('en-PH', {
          ...ph,
          weekday: 'long',
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        });
    }
    updateClock();
    setInterval(updateClock, 1000);

    // ─── ACTIVE LINK ───
    (function() {
      const path = location.pathname;
      document.querySelectorAll('.nav-link').forEach(a => {
        const href = a.getAttribute('href') || '';
        if (
          (path.includes('dashboard') && href.includes('dashboard')) ||
          (path.includes('accounts') && href.includes('accounts'))
          // (path.includes('settings') && href.includes('settings'))
        ) a.classList.add('active');
      });
    })();

    // ─── MOBILE MENU ───
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const menuBtn = document.getElementById('menu-btn');

    function openSidebar() {
      sidebar.classList.add('open');
      overlay.classList.add('show');
      menuBtn.setAttribute('aria-expanded', 'true');
    }

    function closeSidebar() {
      sidebar.classList.remove('open');
      overlay.classList.remove('show');
      menuBtn.setAttribute('aria-expanded', 'false');
    }

    menuBtn.addEventListener('click', () => sidebar.classList.contains('open') ? closeSidebar() : openSidebar());
    overlay.addEventListener('click', closeSidebar);

    // ─── TOAST ───
    const toastIcons = {
      success: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`,
      error: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`,
      info: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`,
      warn: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`,
    };

    window.showToast = function(type = 'info', title = '', msg = '', duration = 4000) {
      const wrap = document.getElementById('toast-wrap');
      const t = document.createElement('div');
      t.className = `toast ${type}`;
      t.innerHTML = `
      <span class="toast-icon">${toastIcons[type] || toastIcons.info}</span>
      <div class="toast-body">
        <div class="toast-title">${title}</div>
        ${msg ? `<div class="toast-msg">${msg}</div>` : ''}
      </div>
      <button class="toast-close" aria-label="Dismiss">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>`;
      wrap.prepend(t);
      const dismiss = () => {
        t.classList.add('out');
        t.addEventListener('animationend', () => t.remove(), {
          once: true
        });
      };
      t.querySelector('.toast-close').addEventListener('click', dismiss);
      if (duration) setTimeout(dismiss, duration);
    };
  </script>

</body>

</html>