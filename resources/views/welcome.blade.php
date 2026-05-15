<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GuenZandra - Welcome</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Syne:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        :root {
            --bg: #080a0f;
            --surface: #0f1218;
            --card: #141820;
            --border: rgba(255, 255, 255, 0.07);
            --border-hover: rgba(201, 168, 76, 0.4);
            --gold: #c9a84c;
            --gold-lt: #e8c97a;
            --gold-dim: rgba(201, 168, 76, 0.12);
            --text: #ede8e0;
            --muted: #696660;
            --subtle: #2a2d35;
            --danger: #d95f5f;
            --success: #52a876;
            --info: #4f7fff;
            --r: 10px;
        }

        html,
        body {
            min-height: 100%;
            font-family: 'Syne', sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden
        }

        .page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            position: relative;
        }

        @media(max-width:860px) {
            .page {
                grid-template-columns: 1fr
            }

            .panel-left {
                display: none
            }
        }

        .panel-left {
            position: relative;
            overflow: hidden;
            background: var(--surface);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem;
            border-right: 1px solid var(--border);
        }

        .panel-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 70% 50% at 30% 20%, rgba(201, 168, 76, .13) 0%, transparent 65%),
                radial-gradient(ellipse 50% 60% at 80% 85%, rgba(201, 168, 76, .06) 0%, transparent 60%);
            pointer-events: none;
        }

        .noise {
            position: absolute;
            inset: 0;
            opacity: .03;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            pointer-events: none;
        }

        .grid-overlay {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(255, 255, 255, .025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .025) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
        }

        .brand {
            position: relative;
            display: flex;
            align-items: center;
            gap: .65rem;
            font-size: .78rem;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--gold);
            font-weight: 600;
        }

        .brand-mark {
            width: 32px;
            height: 32px;
            background: var(--gold-dim);
            border: 1px solid rgba(201, 168, 76, .3);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .left-body {
            position: relative;
        }

        .eyebrow {
            font-size: .7rem;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 1.5rem;
            opacity: .8;
        }

        .headline {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.6rem, 4vw, 3.8rem);
            font-weight: 600;
            line-height: 1.1;
            margin-bottom: 1.4rem;
            color: var(--text);
        }

        .headline em {
            color: var(--gold);
            font-style: italic
        }

        .tagline {
            color: var(--muted);
            font-size: .92rem;
            line-height: 1.75;
            max-width: 340px;
            font-weight: 400;
        }

        .stats {
            position: relative;
            display: flex;
            gap: 2rem;
            padding-top: 2.5rem;
            border-top: 1px solid var(--border);
        }

        .stat-val {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--gold);
            line-height: 1;
            margin-bottom: .3rem;
        }

        .stat-label {
            font-size: .72rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .panel-right {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 2rem;
            position: relative;
            background: var(--bg);
        }

        .panel-right::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 60% 40% at 50% 0%, rgba(201, 168, 76, .06) 0%, transparent 65%);
            pointer-events: none;
        }

        .form-card {
            position: relative;
            width: 100%;
            max-width: 420px;
        }

        .tabs {
            display: flex;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 2rem;
            gap: 4px;
        }

        .tab {
            flex: 1;
            padding: .6rem;
            border-radius: 8px;
            font-family: 'Syne', sans-serif;
            font-size: .82rem;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            transition: all .25s ease;
            color: var(--muted);
            background: transparent;
        }

        .tab.active {
            background: var(--card);
            color: var(--gold);
            border: 1px solid rgba(201, 168, 76, .2);
            box-shadow: 0 2px 12px rgba(0, 0, 0, .3);
        }

        .tab:not(.active):hover {
            color: var(--text)
        }

        .form-panel {
            display: none
        }

        .form-panel.active {
            display: block;
            animation: panelIn .35s ease both
        }

        @keyframes panelIn {
            from {
                opacity: 0;
                transform: translateY(10px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .form-heading {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.9rem;
            font-weight: 600;
            margin-bottom: .35rem;
        }

        .form-sub {
            font-size: .86rem;
            color: var(--muted);
            margin-bottom: 2rem;
            font-weight: 400;
            line-height: 1.6;
        }

        .field {
            margin-bottom: 1.1rem
        }

        .field label {
            display: block;
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: .5rem;
        }

        .input-wrap {
            position: relative
        }

        .input-icon {
            position: absolute;
            left: .9rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            pointer-events: none;
            display: flex;
            align-items: center;
            transition: color .2s;
        }

        .input-wrap input {
            width: 100%;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--r);
            padding: .82rem .9rem .82rem 2.7rem;
            font-family: 'Syne', sans-serif;
            font-size: .92rem;
            color: var(--text);
            outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }

        .input-wrap input::placeholder {
            color: var(--subtle)
        }

        .input-wrap input:focus {
            border-color: var(--gold);
            background: var(--card);
            box-shadow: 0 0 0 3px rgba(201, 168, 76, .1);
        }

        .input-wrap input:focus~.input-icon,
        .input-wrap:focus-within .input-icon {
            color: var(--gold)
        }

        .input-wrap input.err {
            border-color: var(--danger)
        }

        .field-err {
            font-size: .76rem;
            color: var(--danger);
            margin-top: .38rem;
            display: none;
            align-items: center;
            gap: .35rem;
        }

        .field-err.show {
            display: flex
        }

        .pw-btn {
            position: absolute;
            right: .85rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--muted);
            cursor: pointer;
            padding: 4px;
            display: flex;
            transition: color .2s;
            border-radius: 4px;
        }

        .pw-btn:hover {
            color: var(--gold)
        }

        .strength {
            margin-top: .5rem
        }

        .strength-track {
            height: 3px;
            background: var(--subtle);
            border-radius: 100px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            border-radius: 100px;
            transition: width .4s ease, background .4s ease;
        }

        .strength-text {
            font-size: .72rem;
            color: var(--muted);
            margin-top: .3rem;
            font-weight: 500;
        }

        .check-row {
            display: flex;
            align-items: flex-start;
            gap: .65rem;
            font-size: .85rem;
            color: var(--muted);
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .check-row input[type=checkbox] {
            accent-color: var(--gold);
            width: 15px;
            height: 15px;
            flex-shrink: 0;
            margin-top: 2px;
            cursor: pointer;
        }

        .check-row a {
            color: var(--gold);
            text-decoration: none
        }

        .check-row a:hover {
            text-decoration: underline
        }

        .btn-submit {
            width: 100%;
            padding: .9rem;
            background: var(--gold);
            color: #080a0f;
            border: none;
            border-radius: var(--r);
            font-family: 'Syne', sans-serif;
            font-size: .88rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            cursor: pointer;
            transition: all .25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .55rem;
            position: relative;
            overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, .1) 0%, transparent 50%);
        }

        .btn-submit:hover:not(:disabled) {
            background: var(--gold-lt);
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(201, 168, 76, .35);
        }

        .btn-submit:disabled {
            opacity: .55;
            cursor: not-allowed;
            transform: none
        }

        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(8, 10, 15, .25);
            border-top-color: #080a0f;
            border-radius: 50%;
            animation: spin .65s linear infinite;
            flex-shrink: 0;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: .9rem;
            color: var(--muted);
            font-size: .75rem;
            margin: 1.6rem 0;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border)
        }

        .switch-text {
            text-align: center;
            font-size: .84rem;
            color: var(--muted);
            margin-top: 1.4rem;
        }

        .switch-text button {
            background: none;
            border: none;
            color: var(--gold);
            font-family: 'Syne', sans-serif;
            font-size: .84rem;
            font-weight: 600;
            cursor: pointer;
            padding: 0;
            transition: color .2s;
        }

        .switch-text button:hover {
            color: var(--gold-lt);
            text-decoration: underline
        }

        .forgot-link {
            text-align: right;
            margin-top: -0.5rem;
            margin-bottom: 1rem;
        }

        .forgot-link button {
            background: none;
            border: none;
            color: var(--gold);
            font-size: .75rem;
            cursor: pointer;
            transition: color .2s;
        }

        .forgot-link button:hover {
            color: var(--gold-lt);
            text-decoration: underline;
        }

        #toasts {
            position: fixed;
            top: 1.2rem;
            right: 1.2rem;
            z-index: 999;
            display: flex;
            flex-direction: column;
            gap: .6rem;
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
            opacity: 1
        }

        .toast.hide {
            transform: translateX(110%);
            opacity: 0
        }

        .t-icon {
            width: 30px;
            height: 30px;
            flex-shrink: 0;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toast.success .t-icon {
            background: rgba(82, 168, 118, .15);
            color: var(--success)
        }

        .toast.error .t-icon {
            background: rgba(217, 95, 95, .15);
            color: var(--danger)
        }

        .toast.info .t-icon {
            background: var(--gold-dim);
            color: var(--gold)
        }

        .t-title {
            font-size: .84rem;
            font-weight: 600;
            margin-bottom: .12rem
        }

        .toast.success .t-title {
            color: var(--success)
        }

        .toast.error .t-title {
            color: var(--danger)
        }

        .toast.info .t-title {
            color: var(--gold)
        }

        .t-msg {
            font-size: .78rem;
            color: var(--muted);
            line-height: 1.5
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
            background: var(--success)
        }

        .toast.error .t-bar {
            background: var(--danger)
        }

        .toast.info .t-bar {
            background: var(--gold)
        }

        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }

        @keyframes shrink {
            from {
                transform: scaleX(1)
            }

            to {
                transform: scaleX(0)
            }
        }

        @media(max-width:480px) {
            .panel-right {
                padding: 1.5rem 1rem
            }

            #toasts {
                top: auto;
                bottom: 1rem;
                right: .75rem;
                left: .75rem
            }

            .toast {
                min-width: unset;
                max-width: 100%
            }
        }
    </style>
</head>

<body>

    <div class="page">
        <div class="panel-left">
            <div class="noise"></div>
            <div class="grid-overlay"></div>

            <div class="brand">
                <div class="brand-mark">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c9a84c" stroke-width="1.8">
                        <polygon points="12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5 12 2" />
                        <line x1="12" y1="2" x2="12" y2="22" />
                        <polyline points="2 8.5 12 13 22 8.5" />
                    </svg>
                </div>
                GuenZandra
            </div>

            <div class="left-body">
                <p class="eyebrow">✦ A platform for makers</p>
                <h1 class="headline">Your space to<br><em>create &amp; grow.</em></h1>
                <p class="tagline">A refined platform built for people who take their work seriously. Join thousands already inside crafting what matters.</p>
            </div>

            <div class="stats" id="statsContainer">
                <div class="stat-item">
                    <div class="stat-val" id="totalUsers">—</div>
                    <div class="stat-label">Members</div>
                </div>
                <div class="stat-item">
                    <div class="stat-val" id="newUsers">—</div>
                    <div class="stat-label">Joined this month</div>
                </div>
                <div class="stat-item">
                    <div class="stat-val" id="activeUsers">—</div>
                    <div class="stat-label">Active now</div>
                </div>
            </div>
        </div>

        <div class="panel-right">
            <div class="form-card">
                <div class="tabs" role="tablist">
                    <button class="tab active" role="tab" onclick="switchTab('login')">Sign In</button>
                    <button class="tab" role="tab" onclick="switchTab('register')">Register</button>
                </div>

                <!-- LOGIN FORM -->
                <div class="form-panel active" id="panel-login">
                    <h2 class="form-heading">Welcome back</h2>
                    <p class="form-sub">Sign in to continue to your account.</p>

                    <div class="field">
                        <label for="l-email">Email Address</label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                    <polyline points="22,6 12,13 2,6" />
                                </svg>
                            </span>
                            <input type="email" id="l-email" placeholder="you@example.com" autocomplete="email">
                        </div>
                        <div class="field-err" id="l-email-err">Please enter a valid email address.</div>
                    </div>

                    <div class="field">
                        <label for="l-pw">Password</label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                            </span>
                            <input type="password" id="l-pw" placeholder="••••••••" autocomplete="current-password">
                            <button type="button" class="pw-btn" onclick="togglePw('l-pw',this)">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>
                        </div>
                        <div class="field-err" id="l-pw-err">Password must be at least 6 characters.</div>
                    </div>

                    <div class="forgot-link">
                        <button onclick="showForgotPassword()">Forgot password?</button>
                    </div>

                    <button class="btn-submit" id="l-btn" onclick="submitLogin()">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                            <polyline points="10 17 15 12 10 7" />
                            <line x1="15" y1="12" x2="3" y2="12" />
                        </svg>
                        Sign In
                    </button>

                    <div class="switch-text">
                        Don't have an account? <button onclick="switchTab('register')">Create one</button>
                    </div>
                </div>

                <!-- REGISTER FORM -->
                <div class="form-panel" id="panel-register">
                    <h2 class="form-heading">Create account</h2>
                    <p class="form-sub">Join us — it only takes a moment.</p>

                    <div class="field">
                        <label for="r-name">Full Name</label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </span>
                            <input type="text" id="r-name" placeholder="Juan dela Cruz" autocomplete="name">
                        </div>
                        <div class="field-err" id="r-name-err">Name must be at least 2 characters.</div>
                    </div>

                    <div class="field">
                        <label for="r-email">Email Address</label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                    <polyline points="22,6 12,13 2,6" />
                                </svg>
                            </span>
                            <input type="email" id="r-email" placeholder="you@example.com" autocomplete="email">
                        </div>
                        <div class="field-err" id="r-email-err">Please enter a valid email address.</div>
                    </div>

                    <div class="field">
                        <label for="r-pw">Password</label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                            </span>
                            <input type="password" id="r-pw" placeholder="••••••••" oninput="checkStrength(this.value)" autocomplete="new-password">
                            <button type="button" class="pw-btn" onclick="togglePw('r-pw',this)">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>
                        </div>
                        <div class="field-err" id="r-pw-err">Password must be at least 8 characters with uppercase, lowercase, and number.</div>
                        <div class="strength">
                            <div class="strength-track">
                                <div class="strength-fill" id="s-fill"></div>
                            </div>
                            <div class="strength-text" id="s-label"></div>
                        </div>
                    </div>

                    <div class="field">
                        <label for="r-confirm">Confirm Password</label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                </svg>
                            </span>
                            <input type="password" id="r-confirm" placeholder="••••••••" autocomplete="new-password">
                            <button type="button" class="pw-btn" onclick="togglePw('r-confirm',this)">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>
                        </div>
                        <div class="field-err" id="r-confirm-err">Passwords do not match.</div>
                    </div>

                    <div class="check-row">
                        <input type="checkbox" id="r-terms">
                        <label for="r-terms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
                    </div>
                    <div class="field-err" id="r-terms-err">You must accept the terms to continue.</div>

                    <button class="btn-submit" id="r-btn" onclick="submitRegister()">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="8.5" cy="7" r="4" />
                            <line x1="20" y1="8" x2="20" y2="14" />
                            <line x1="23" y1="11" x2="17" y2="11" />
                        </svg>
                        Create Account
                    </button>

                    <div class="switch-text">
                        Already have an account? <button onclick="switchTab('login')">Sign in</button>
                    </div>
                </div>

                <!-- FORGOT PASSWORD MODAL (inline) -->
                <div class="form-panel" id="panel-forgot">
                    <h2 class="form-heading">Reset password</h2>
                    <p class="form-sub">Enter your email to receive a reset code.</p>

                    <div class="field">
                        <label for="f-email">Email Address</label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                    <polyline points="22,6 12,13 2,6" />
                                </svg>
                            </span>
                            <input type="email" id="f-email" placeholder="you@example.com">
                        </div>
                        <div class="field-err" id="f-email-err">Please enter a valid email address.</div>
                    </div>

                    <button class="btn-submit" id="f-btn" onclick="sendResetCode()">
                        Send Reset Code
                    </button>

                    <div class="switch-text">
                        <button onclick="switchTab('login')">← Back to Sign In</button>
                    </div>
                </div>

                <!-- VERIFY CODE MODAL -->
                <div class="form-panel" id="panel-verify">
                    <h2 class="form-heading">Verify Code</h2>
                    <p class="form-sub">Enter the 6-digit code sent to your email.</p>

                    <div class="field">
                        <label for="v-code">Verification Code</label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                            </span>
                            <input type="text" id="v-code" placeholder="000000" maxlength="6">
                        </div>
                        <div class="field-err" id="v-code-err">Invalid or expired code.</div>
                    </div>

                    <button class="btn-submit" id="v-btn" onclick="verifyResetCode()">
                        Verify Code
                    </button>

                    <div class="switch-text">
                        <button onclick="showForgotPassword()">← Resend Code</button>
                    </div>
                </div>

                <!-- RESET PASSWORD MODAL -->
                <div class="form-panel" id="panel-reset">
                    <h2 class="form-heading">New Password</h2>
                    <p class="form-sub">Create a new strong password.</p>

                    <div class="field">
                        <label for="reset-pw">New Password</label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                            </span>
                            <input type="password" id="reset-pw" placeholder="••••••••" oninput="checkResetStrength(this.value)">
                            <button type="button" class="pw-btn" onclick="togglePw('reset-pw',this)">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>
                        </div>
                        <div class="field-err" id="reset-pw-err">Password must be at least 8 characters with uppercase, lowercase, and number.</div>
                        <div class="strength">
                            <div class="strength-track">
                                <div class="strength-fill" id="reset-fill"></div>
                            </div>
                            <div class="strength-text" id="reset-label"></div>
                        </div>
                    </div>

                    <div class="field">
                        <label for="reset-confirm">Confirm Password</label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                </svg>
                            </span>
                            <input type="password" id="reset-confirm" placeholder="••••••••">
                            <button type="button" class="pw-btn" onclick="togglePw('reset-confirm',this)">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>
                        </div>
                        <div class="field-err" id="reset-confirm-err">Passwords do not match.</div>
                    </div>

                    <button class="btn-submit" id="reset-btn" onclick="resetPassword()">
                        Reset Password
                    </button>

                    <div class="switch-text">
                        <button onclick="switchTab('login')">← Back to Sign In</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="toasts" aria-live="polite"></div>

    <script>
        let resetEmail = '';
        let resetCode = '';

        // Load statistics
        async function loadStatistics() {
            try {
                const response = await fetch('/statistics');
                const result = await response.json();
                if (result.success) {
                    document.getElementById('totalUsers').textContent = formatNumber(result.data.total_users);
                    document.getElementById('newUsers').textContent = formatNumber(result.data.new_this_month);
                    document.getElementById('activeUsers').textContent = formatNumber(result.data.active_users);
                }
            } catch (error) {
                console.error('Error loading statistics:', error);
            }
        }

        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function switchTab(name) {
            document.querySelectorAll('.tab').forEach((t, i) => {
                const isActive = (i === 0 && name === 'login') || (i === 1 && name === 'register');
                t.classList.toggle('active', isActive);
            });
            document.querySelectorAll('.form-panel').forEach(p => p.classList.remove('active'));
            document.getElementById('panel-' + name).classList.add('active');
            clearAllErrors();
        }

        function showForgotPassword() {
            clearAllErrors();
            document.getElementById('f-email').value = '';
            document.querySelectorAll('.form-panel').forEach(p => p.classList.remove('active'));
            document.getElementById('panel-forgot').classList.add('active');
        }

        function clearAllErrors() {
            document.querySelectorAll('.field-err').forEach(e => e.classList.remove('show'));
            document.querySelectorAll('input').forEach(e => e.classList.remove('err'));
        }

        function showErr(inputId, errId) {
            const el = document.getElementById(inputId);
            if (el) el.classList.add('err');
            const er = document.getElementById(errId);
            if (er) er.classList.add('show');
        }

        function clearErr(inputId, errId) {
            const el = document.getElementById(inputId);
            if (el) el.classList.remove('err');
            const er = document.getElementById(errId);
            if (er) er.classList.remove('show');
        }

        function togglePw(id, btn) {
            const inp = document.getElementById(id);
            const hidden = inp.type === 'password';
            inp.type = hidden ? 'text' : 'password';
            btn.innerHTML = hidden ?
                `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>` :
                `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
        }

        function checkStrength(v) {
            const fill = document.getElementById('s-fill');
            const label = document.getElementById('s-label');
            if (!v) {
                fill.style.width = '0%';
                label.textContent = '';
                return
            }
            let score = 0;
            if (v.length >= 8) score++;
            if (v.length >= 12) score++;
            if (/[A-Z]/.test(v)) score++;
            if (/[0-9]/.test(v)) score++;
            if (/[^A-Za-z0-9]/.test(v)) score++;
            const lvls = [{
                w: '20%',
                c: '#d95f5f',
                t: 'Weak'
            }, {
                w: '40%',
                c: '#d98c5f',
                t: 'Fair'
            }, {
                w: '60%',
                c: '#d9c25f',
                t: 'Good'
            }, {
                w: '80%',
                c: '#8ec25f',
                t: 'Strong'
            }, {
                w: '100%',
                c: '#52a876',
                t: 'Excellent'
            }];
            const l = lvls[Math.min(score - 1, 4)] || lvls[0];
            fill.style.width = l.w;
            fill.style.background = l.c;
            label.textContent = l.t;
            label.style.color = l.c;
        }

        function checkResetStrength(v) {
            const fill = document.getElementById('reset-fill');
            const label = document.getElementById('reset-label');
            if (!v) {
                fill.style.width = '0%';
                label.textContent = '';
                return
            }
            let score = 0;
            if (v.length >= 8) score++;
            if (v.length >= 12) score++;
            if (/[A-Z]/.test(v)) score++;
            if (/[0-9]/.test(v)) score++;
            if (/[^A-Za-z0-9]/.test(v)) score++;
            const lvls = [{
                w: '20%',
                c: '#d95f5f',
                t: 'Weak'
            }, {
                w: '40%',
                c: '#d98c5f',
                t: 'Fair'
            }, {
                w: '60%',
                c: '#d9c25f',
                t: 'Good'
            }, {
                w: '80%',
                c: '#8ec25f',
                t: 'Strong'
            }, {
                w: '100%',
                c: '#52a876',
                t: 'Excellent'
            }];
            const l = lvls[Math.min(score - 1, 4)] || lvls[0];
            fill.style.width = l.w;
            fill.style.background = l.c;
            label.textContent = l.t;
            label.style.color = l.c;
        }

        function isEmail(v) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim())
        }

        function setLoading(id, on) {
            const b = document.getElementById(id);
            if (on) {
                b.disabled = true;
                b._orig = b.innerHTML;
                b.innerHTML = `<span class="spinner"></span> Please wait…`
            } else {
                b.disabled = false;
                b.innerHTML = b._orig
            }
        }

        async function submitLogin() {
            clearAllErrors();
            const email = document.getElementById('l-email').value.trim();
            const pw = document.getElementById('l-pw').value;
            let ok = true;

            if (!isEmail(email)) {
                showErr('l-email', 'l-email-err');
                ok = false
            }
            if (pw.length < 6) {
                showErr('l-pw', 'l-pw-err');
                ok = false
            }

            if (!ok) return;

            setLoading('l-btn', true);

            try {
                const response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email,
                        password: pw
                    })
                });

                const result = await response.json();

                if (result.success) {
                    toast('success', 'Success!', result.message);
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 1000);
                } else {
                    toast('error', 'Login Failed', result.message);
                }
            } catch (error) {
                toast('error', 'Error', 'Something went wrong. Please try again.');
            } finally {
                setLoading('l-btn', false);
            }
        }

        async function submitRegister() {
            clearAllErrors();
            const name = document.getElementById('r-name').value.trim();
            const email = document.getElementById('r-email').value.trim();
            const pw = document.getElementById('r-pw').value;
            const confirm = document.getElementById('r-confirm').value;
            const terms = document.getElementById('r-terms').checked;
            let ok = true;

            if (name.length < 2) {
                showErr('r-name', 'r-name-err');
                ok = false
            }
            if (!isEmail(email)) {
                showErr('r-email', 'r-email-err');
                ok = false
            }
            if (pw.length < 8 || !/[A-Z]/.test(pw) || !/[a-z]/.test(pw) || !/[0-9]/.test(pw)) {
                showErr('r-pw', 'r-pw-err');
                ok = false
            }
            if (pw !== confirm) {
                showErr('r-confirm', 'r-confirm-err');
                ok = false
            }
            if (!terms) {
                document.getElementById('r-terms-err').classList.add('show');
                ok = false
            }

            if (!ok) return;

            setLoading('r-btn', true);

            try {
                const response = await fetch('/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name,
                        email,
                        password: pw,
                        terms: true
                    })
                });

                const result = await response.json();

                if (result.success) {
                    toast('success', 'Account created!', result.message);
                    document.getElementById('r-name').value = '';
                    document.getElementById('r-email').value = '';
                    document.getElementById('r-pw').value = '';
                    document.getElementById('r-confirm').value = '';
                    document.getElementById('r-terms').checked = false;
                    document.getElementById('s-fill').style.width = '0%';
                    document.getElementById('s-label').textContent = '';

                    setTimeout(() => {
                        switchTab('login');
                    }, 1500);
                } else {
                    if (result.errors) {
                        const errors = Object.values(result.errors).flat().join('\n');
                        toast('error', 'Registration Failed', errors);
                    } else {
                        toast('error', 'Registration Failed', result.message);
                    }
                }
            } catch (error) {
                toast('error', 'Error', 'Something went wrong. Please try again.');
            } finally {
                setLoading('r-btn', false);
            }
        }

        async function sendResetCode() {
            clearAllErrors();
            const email = document.getElementById('f-email').value.trim();

            if (!isEmail(email)) {
                showErr('f-email', 'f-email-err');
                return;
            }

            resetEmail = email;
            setLoading('f-btn', true);

            try {
                const response = await fetch('/send-reset-code', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email
                    })
                });

                const result = await response.json();

                if (result.success) {
                    toast('success', 'Code Sent!', result.message);
                    document.querySelectorAll('.form-panel').forEach(p => p.classList.remove('active'));
                    document.getElementById('panel-verify').classList.add('active');
                    document.getElementById('v-code').value = '';
                } else {
                    toast('error', 'Failed', result.message);
                }
            } catch (error) {
                toast('error', 'Error', 'Something went wrong. Please try again.');
            } finally {
                setLoading('f-btn', false);
            }
        }

        async function verifyResetCode() {
            clearAllErrors();
            const code = document.getElementById('v-code').value.trim();

            if (code.length !== 6) {
                showErr('v-code', 'v-code-err');
                return;
            }

            resetCode = code;
            setLoading('v-btn', true);

            try {
                const response = await fetch('/verify-reset-code', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: resetEmail,
                        code
                    })
                });

                const result = await response.json();

                if (result.success) {
                    toast('success', 'Code Verified!', result.message);
                    document.querySelectorAll('.form-panel').forEach(p => p.classList.remove('active'));
                    document.getElementById('panel-reset').classList.add('active');
                    document.getElementById('reset-pw').value = '';
                    document.getElementById('reset-confirm').value = '';
                    document.getElementById('reset-fill').style.width = '0%';
                    document.getElementById('reset-label').textContent = '';
                } else {
                    toast('error', 'Verification Failed', result.message);
                }
            } catch (error) {
                toast('error', 'Error', 'Something went wrong. Please try again.');
            } finally {
                setLoading('v-btn', false);
            }
        }

        async function resetPassword() {
            clearAllErrors();
            const password = document.getElementById('reset-pw').value;
            const confirm = document.getElementById('reset-confirm').value;
            let ok = true;

            if (password.length < 8 || !/[A-Z]/.test(password) || !/[a-z]/.test(password) || !/[0-9]/.test(password)) {
                showErr('reset-pw', 'reset-pw-err');
                ok = false;
            }
            if (password !== confirm) {
                showErr('reset-confirm', 'reset-confirm-err');
                ok = false;
            }

            if (!ok) return;

            setLoading('reset-btn', true);

            try {
                const response = await fetch('/reset-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: resetEmail,
                        code: resetCode,
                        password,
                        password_confirmation: confirm
                    })
                });

                const result = await response.json();

                if (result.success) {
                    toast('success', 'Password Reset!', result.message);
                    setTimeout(() => {
                        switchTab('login');
                        document.getElementById('l-email').value = resetEmail;
                    }, 1500);
                } else {
                    toast('error', 'Reset Failed', result.message);
                }
            } catch (error) {
                toast('error', 'Error', 'Something went wrong. Please try again.');
            } finally {
                setLoading('reset-btn', false);
            }
        }

        let tc = 0;
        const icons = {
            success: `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>`,
            error: `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`,
            info: `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>`
        };

        function toast(type, title, msg) {
            const id = 't' + (++tc);
            const el = document.createElement('div');
            el.className = 'toast ' + type;
            el.id = id;
            el.setAttribute('role', 'alert');
            el.innerHTML = `<div class="t-icon">${icons[type]||icons.info}</div><div><div class="t-title">${title}</div><div class="t-msg">${msg}</div></div><div class="t-bar"></div>`;
            document.getElementById('toasts').appendChild(el);
            requestAnimationFrame(() => requestAnimationFrame(() => el.classList.add('show')));
            setTimeout(() => {
                el.classList.add('hide');
                el.addEventListener('transitionend', () => el.remove(), {
                    once: true
                });
            }, 3900);
        }

        // Initialize
        window.addEventListener('DOMContentLoaded', () => {
            loadStatistics();
            // Auto-refresh stats every minute
            setInterval(loadStatistics, 60000);
        });
    </script>
</body>

</html>