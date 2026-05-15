<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Guen') }} — @yield('title', 'Dashboard')</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Syne:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --bg: #080a0f;
      --surface: #0f1218;
      --card: #141820;
      --border: rgba(255, 255, 255, 0.07);
      --gold: #c9a84c;
      --gold-lt: #e8c97a;
      --gold-dim: rgba(201, 168, 76, 0.10);
      --gold-border: rgba(201, 168, 76, 0.25);
      --text: #ede8e0;
      --muted: #696660;
      --subtle: #1e2229;
      --danger: #d95f5f;
      --danger-dim: rgba(217, 95, 95, 0.10);
      --success: #52a876;
      --sidebar-w: 260px;
    }

    html,
    body {
      height: 100%;
      font-family: 'Syne', sans-serif;
      background: var(--bg);
      color: var(--text);
      overflow-x: hidden;
    }

    /* ── APP SHELL ── */
    .app-shell {
      display: flex;
      min-height: 100vh;
    }

    /* ── SIDEBAR SLOT ── */
    .sidebar-slot {
      width: var(--sidebar-w);
      flex-shrink: 0;
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      z-index: 200;
      transition: transform .3s cubic-bezier(.4, 0, .2, 1);
    }

    /* ── MAIN CONTENT AREA ── */
    .main-wrap {
      flex: 1;
      margin-left: var(--sidebar-w);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      background: var(--bg);
      position: relative;
    }

    .main-wrap::before {
      content: '';
      position: fixed;
      top: 0;
      left: var(--sidebar-w);
      right: 0;
      height: 260px;
      background: radial-gradient(ellipse 60% 100% at 60% 0%, rgba(201, 168, 76, .05) 0%, transparent 70%);
      pointer-events: none;
      z-index: 0;
    }

    .page-content {
      position: relative;
      z-index: 1;
      flex: 1;
      padding: 2rem 2.5rem 3rem;
    }

    /* ── PAGE HEADER helper classes (use inside @yield('content')) ── */
    .page-header {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 1rem;
      margin-bottom: 2rem;
      padding-bottom: 1.5rem;
      border-bottom: 1px solid var(--border);
    }

    .page-eyebrow {
      font-size: .65rem;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: var(--gold);
      font-weight: 600;
      margin-bottom: .35rem;
    }

    .page-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(1.6rem, 3vw, 2.2rem);
      font-weight: 600;
      line-height: 1.15;
    }

    .page-sub {
      font-size: .84rem;
      color: var(--muted);
      margin-top: .3rem;
      font-weight: 400;
    }

    /* ── MOBILE OVERLAY ── */
    .nav-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .65);
      backdrop-filter: blur(4px);
      z-index: 199;
      opacity: 0;
      transition: opacity .3s;
      pointer-events: none;
    }

    .nav-overlay.open {
      opacity: 1;
      pointer-events: all;
    }

    /* ── MOBILE HAMBURGER ── */
    .nav-toggle {
      display: none;
      position: fixed;
      top: 1rem;
      left: 1rem;
      z-index: 300;
      width: 40px;
      height: 40px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 10px;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      color: var(--text);
      transition: border-color .2s, color .2s;
    }

    .nav-toggle:hover {
      border-color: var(--gold);
      color: var(--gold);
    }

    /* ── GLOBAL TOAST ── */
    #toast-container {
      position: fixed;
      top: 1.2rem;
      right: 1.2rem;
      z-index: 999;
      display: flex;
      flex-direction: column;
      gap: .55rem;
      pointer-events: none;
    }

    .toast {
      display: flex;
      align-items: flex-start;
      gap: .7rem;
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: .85rem 1rem;
      min-width: 270px;
      max-width: 340px;
      box-shadow: 0 8px 28px rgba(0, 0, 0, .5);
      transform: translateX(110%);
      opacity: 0;
      transition: transform .4s cubic-bezier(.34, 1.56, .64, 1), opacity .3s;
      pointer-events: all;
      position: relative;
      overflow: hidden;
    }

    .toast.show {
      transform: translateX(0);
      opacity: 1;
    }

    .toast.hide {
      transform: translateX(110%);
      opacity: 0;
    }

    .t-icon {
      width: 30px;
      height: 30px;
      border-radius: 7px;
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .toast.success .t-icon {
      background: rgba(82, 168, 118, .15);
      color: var(--success);
    }

    .toast.error .t-icon {
      background: rgba(217, 95, 95, .15);
      color: var(--danger);
    }

    .toast.info .t-icon {
      background: var(--gold-dim);
      color: var(--gold);
    }

    .t-title {
      font-size: .84rem;
      font-weight: 600;
      margin-bottom: .12rem;
    }

    .toast.success .t-title {
      color: var(--success);
    }

    .toast.error .t-title {
      color: var(--danger);
    }

    .toast.info .t-title {
      color: var(--gold);
    }

    .t-msg {
      font-size: .78rem;
      color: var(--muted);
      line-height: 1.5;
    }

    .t-bar {
      position: absolute;
      bottom: 0;
      left: 0;
      height: 2px;
      width: 100%;
      transform-origin: left;
      animation: shrink 3.8s linear forwards;
    }

    .toast.success .t-bar {
      background: var(--success);
    }

    .toast.error .t-bar {
      background: var(--danger);
    }

    .toast.info .t-bar {
      background: var(--gold);
    }

    @keyframes shrink {
      from {
        transform: scaleX(1);
      }

      to {
        transform: scaleX(0);
      }
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
      .sidebar-slot {
        transform: translateX(-100%);
      }

      .sidebar-slot.open {
        transform: translateX(0);
      }

      .nav-toggle {
        display: flex;
      }

      .nav-overlay {
        display: block;
      }

      .main-wrap {
        margin-left: 0;
      }

      .main-wrap::before {
        left: 0;
      }

      .page-content {
        padding: 4.5rem 1.25rem 2rem;
      }

      #toast-container {
        top: auto;
        bottom: 1rem;
        right: .75rem;
        left: .75rem;
      }

      .toast {
        min-width: unset;
        max-width: 100%;
      }
    }
  </style>

  @stack('styles')
</head>

<body>

  {{-- Mobile hamburger button --}}
  <button class="nav-toggle" id="nav-toggle" aria-label="Toggle navigation" onclick="toggleNav()">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <line x1="3" y1="6" x2="21" y2="6" />
      <line x1="3" y1="12" x2="21" y2="12" />
      <line x1="3" y1="18" x2="21" y2="18" />
    </svg>
  </button>

  {{-- Mobile backdrop --}}
  <div class="nav-overlay" id="nav-overlay" onclick="toggleNav()"></div>

  <div class="app-shell">

    {{-- ══ LEFT PANEL ══ --}}
    <div class="sidebar-slot" id="sidebar-slot">
      @include('nav')
    </div>

    {{-- ══ RIGHT PANEL ══ --}}
    <main class="main-wrap" id="main-wrap">
      <div class="page-content">
        @yield('content')
      </div>
    </main>

  </div>

  {{-- Global toast container --}}
  <div id="toast-container" aria-live="polite"></div>

  {{-- Laravel flash messages auto-toast --}}
  @if(session('success'))
  <script>
    document.addEventListener('DOMContentLoaded', () => showToast('success', 'Success', '{{ addslashes(session('
      success ')) }}'));
  </script>
  @endif
  @if(session('error'))
  <script>
    document.addEventListener('DOMContentLoaded', () => showToast('error', 'Error', '{{ addslashes(session('
      error ')) }}'));
  </script>
  @endif
  @if(session('info'))
  <script>
    document.addEventListener('DOMContentLoaded', () => showToast('info', 'Notice', '{{ addslashes(session('
      info ')) }}'));
  </script>
  @endif

  <script>
    // ── Mobile nav toggle ────────────────────────────────────────
    function toggleNav() {
      const slot = document.getElementById('sidebar-slot');
      const overlay = document.getElementById('nav-overlay');
      const isOpen = slot.classList.contains('open');
      slot.classList.toggle('open', !isOpen);
      overlay.classList.toggle('open', !isOpen);
    }

    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') {
        document.getElementById('sidebar-slot').classList.remove('open');
        document.getElementById('nav-overlay').classList.remove('open');
      }
    });

    // ── Global showToast(type, title, msg) ───────────────────────
    const _icons = {
      success: `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`,
      error: `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`,
      info: `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>`
    };

    function showToast(type, title, msg) {
      const el = document.createElement('div');
      el.className = 'toast ' + type;
      el.setAttribute('role', 'alert');
      el.innerHTML = `<div class="t-icon">${_icons[type]||_icons.info}</div>
            <div><div class="t-title">${title}</div><div class="t-msg">${msg}</div></div>
            <div class="t-bar"></div>`;
      document.getElementById('toast-container').appendChild(el);
      requestAnimationFrame(() => requestAnimationFrame(() => el.classList.add('show')));
      setTimeout(() => {
        el.classList.add('hide');
        el.addEventListener('transitionend', () => el.remove(), {
          once: true
        });
      }, 3900);
    }
  </script>

  @stack('scripts')
</body>

</html>