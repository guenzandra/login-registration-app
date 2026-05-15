{{-- resources/views/admin/dashboard.blade.php --}}
@extends('admin.layout')

@section('content')

<style>
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .page-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text);
        letter-spacing: -0.02em;
    }

    .page-subtitle {
        font-size: 0.82rem;
        color: var(--muted);
        margin-top: 3px;
    }

    .page-actions {
        display: flex;
        gap: 10px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 16px;
        border-radius: var(--radius);
        font-size: 0.82rem;
        font-weight: 600;
        font-family: var(--font);
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        border: none;
    }

    .btn svg {
        width: 15px;
        height: 15px;
        flex-shrink: 0;
        stroke: currentColor;
    }

    .btn-primary {
        background: var(--accent);
        color: #fff;
    }

    .btn-primary:hover {
        background: #3d6eef;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px var(--accent-glow);
    }

    .btn-ghost {
        background: transparent;
        color: var(--muted);
        border: 1px solid var(--border2);
    }

    .btn-ghost:hover {
        background: var(--surface2);
        color: var(--text);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 22px 24px;
        position: relative;
        overflow: hidden;
        transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s;
        cursor: default;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    }

    .stat-card.blue::before {
        background: linear-gradient(90deg, #4f7fff, #7ba5ff);
    }

    .stat-card.green::before {
        background: linear-gradient(90deg, #34d399, #6ee7b7);
    }

    .stat-card.purple::before {
        background: linear-gradient(90deg, #a78bfa, #c4b5fd);
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 28px rgba(0, 0, 0, 0.35);
        border-color: var(--border2);
    }

    .stat-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .stat-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: var(--muted);
    }

    .stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
    }

    .stat-icon.blue {
        background: rgba(79, 127, 255, 0.14);
        color: #4f7fff;
    }

    .stat-icon.green {
        background: rgba(52, 211, 153, 0.14);
        color: #34d399;
    }

    .stat-icon.purple {
        background: rgba(167, 139, 250, 0.14);
        color: #a78bfa;
    }

    .stat-number {
        font-size: 2.2rem;
        font-weight: 700;
        letter-spacing: -0.03em;
        line-height: 1;
        margin-bottom: 6px;
        counter-reset: count attr(data-val);
    }

    .stat-number.blue {
        color: #4f7fff;
    }

    .stat-number.green {
        color: #34d399;
    }

    .stat-number.purple {
        color: #a78bfa;
    }

    .stat-desc {
        font-size: 0.78rem;
        color: var(--muted);
    }

    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 20px;
        margin-top: 8px;
    }

    .stat-badge.up {
        background: rgba(52, 211, 153, 0.12);
        color: #34d399;
    }

    .stat-badge.down {
        background: rgba(255, 92, 92, 0.12);
        color: #ff5c5c;
    }

    .stat-badge svg {
        width: 10px;
        height: 10px;
        stroke: currentColor;
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .section-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title svg {
        width: 16px;
        height: 16px;
        stroke: var(--accent);
    }

    .table-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .table-card table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-card thead {
        background: rgba(255, 255, 255, 0.03);
    }

    .table-card th {
        padding: 13px 18px;
        text-align: left;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--muted);
        white-space: nowrap;
        border-bottom: 1px solid var(--border);
    }

    .table-card td {
        padding: 14px 18px;
        font-size: 0.84rem;
        color: var(--muted);
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    .table-card tbody tr:last-child td {
        border-bottom: none;
    }

    .table-card tbody tr {
        transition: background 0.15s;
    }

    .table-card tbody tr:hover {
        background: var(--surface2);
    }

    .empty-state {
        text-align: center;
        padding: 56px 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }

    .empty-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: var(--surface2);
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--muted);
    }

    .empty-icon svg {
        width: 24px;
        height: 24px;
        stroke: currentColor;
    }

    .empty-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text);
    }

    .empty-desc {
        font-size: 0.8rem;
        color: var(--muted);
        max-width: 260px;
        line-height: 1.6;
    }

    .status-dot {
        display: inline-block;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        margin-right: 7px;
        vertical-align: middle;
    }

    .status-dot.active {
        background: var(--success);
        box-shadow: 0 0 0 3px rgba(52, 211, 153, 0.2);
    }

    .status-dot.pending {
        background: var(--warn);
    }

    .status-dot.inactive {
        background: var(--muted);
    }

    .quick-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
        margin-bottom: 24px;
    }

    .quick-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 18px 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 10px;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }

    .quick-card:hover {
        border-color: var(--accent);
        background: var(--accent-dim);
        transform: translateY(-2px);
    }

    .quick-card svg {
        width: 22px;
        height: 22px;
        stroke: var(--accent);
    }

    .quick-card span {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text);
    }

    .quick-card small {
        font-size: 0.72rem;
        color: var(--muted);
    }

    @keyframes countUp {
        from {
            opacity: 0;
            transform: translateY(6px);
        }

        to {
            opacity: 1;
            transform: none;
        }
    }

    .stat-number {
        animation: countUp 0.6s ease both;
    }

    .stat-number:nth-child(1) {
        animation-delay: 0.1s;
    }

    @media (max-width: 600px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .quick-grid {
            grid-template-columns: 1fr;
        }

        .table-card {
            overflow-x: auto;
        }
    }
</style>

<div class="page-header">
    <div>
        <div class="page-title">Dashboard</div>
        <div class="page-subtitle">Welcome back — here's what's happening.</div>
    </div>
    <div class="page-actions">
        <button class="btn btn-ghost" onclick="refreshDashboard()">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="23 4 23 10 17 10" />
                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10" />
            </svg>
            Refresh
        </button>
        <a href="{{ route('admin.accounts') }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Add User
        </a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-card-top">
            <span class="stat-label">Total Accounts</span>
            <span class="stat-icon blue">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
            </span>
        </div>
        <div class="stat-number blue" id="totalAccounts">{{ number_format($totalAccounts ?? 0) }}</div>
        <div class="stat-desc">Registered users in the system</div>
        <div class="stat-badge up">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="18 15 12 9 6 15" />
            </svg>
            All time
        </div>
    </div>

    <div class="stat-card green">
        <div class="stat-card-top">
            <span class="stat-label">New This Month</span>
            <span class="stat-icon green">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <line x1="19" y1="8" x2="19" y2="14" />
                    <line x1="22" y1="11" x2="16" y2="11" />
                </svg>
            </span>
        </div>
        <div class="stat-number green" id="newThisMonth">{{ number_format($newAccountsThisMonth ?? 0) }}</div>
        <div class="stat-desc">Registered in the last 30 days</div>
        <div class="stat-badge up">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="18 15 12 9 6 15" />
            </svg>
            This month
        </div>
    </div>

    <div class="stat-card purple">
        <div class="stat-card-top">
            <span class="stat-label">Active Sessions</span>
            <span class="stat-icon purple">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
            </span>
        </div>
        <div class="stat-number purple" id="activeSessions">{{ number_format($activeSessions ?? 0) }}</div>
        <div class="stat-desc">Currently logged-in users</div>
        <div class="stat-badge up">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="18 15 12 9 6 15" />
            </svg>
            Live now
        </div>
    </div>
</div>

<div class="section-header">
    <div class="section-title">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
        </svg>
        Quick Actions
    </div>
</div>

<div class="quick-grid">
    <a href="{{ route('admin.accounts') }}" class="quick-card">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <line x1="19" y1="8" x2="19" y2="14" />
            <line x1="22" y1="11" x2="16" y2="11" />
        </svg>
        <span>Add User</span>
        <small>Create a new account</small>
    </a>
    <a href="{{ route('admin.accounts') }}" class="quick-card">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8" />
            <line x1="21" y1="21" x2="16.65" y2="16.65" />
        </svg>
        <span>Manage Users</span>
        <small>View and manage all users</small>
    </a>
</div>

<div class="section-header">
    <div class="section-title">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
        </svg>
        Recent Activity
    </div>
    <button class="btn btn-ghost" style="padding:6px 12px; font-size:0.76rem;" onclick="loadRecentActivity()">
        Refresh
    </button>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Activity</th>
                <th>User</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody id="activityTableBody">
            <tr>
                <td colspan="4">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="3" />
                                <path d="M9 9h6M9 13h4" />
                            </svg>
                        </div>
                        <div class="empty-title">No activity yet</div>
                        <div class="empty-desc">Recent activity from users will appear here.</div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    // Toast notification function
    function showToast(title, message, type = 'success', duration = 5000) {
        let container = document.getElementById('toastContainer');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container';
            container.id = 'toastContainer';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;

        const icons = {
            success: '<i class="fas fa-check-circle" style="color: var(--success);"></i>',
            error: '<i class="fas fa-exclamation-circle" style="color: var(--danger);"></i>',
            warning: '<i class="fas fa-exclamation-triangle" style="color: var(--warning);"></i>',
            info: '<i class="fas fa-info-circle" style="color: var(--info);"></i>'
        };

        toast.innerHTML = `
            <div class="toast-icon">${icons[type] || icons.success}</div>
            <div class="toast-content">
                <div class="toast-title">${escapeHtml(title)}</div>
                <div class="toast-message">${escapeHtml(message)}</div>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
        `;

        container.appendChild(toast);

        setTimeout(() => {
            if (toast.parentElement) toast.remove();
        }, duration);
    }

    function escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    // Format number with commas
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    // Update statistics via AJAX
    async function updateStatistics() {
        try {
            const response = await fetch('/admin/dashboard/statistics', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (result.success) {
                document.getElementById('totalAccounts').textContent = formatNumber(result.data.total_accounts);
                document.getElementById('newThisMonth').textContent = formatNumber(result.data.new_this_month);
                document.getElementById('activeSessions').textContent = formatNumber(result.data.active_sessions);
            }
        } catch (error) {
            console.error('Error updating statistics:', error);
        }
    }

    // Load recent activity
    async function loadRecentActivity() {
        try {
            const response = await fetch('/admin/dashboard/activity', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (result.success && result.data.length > 0) {
                renderActivity(result.data);
            } else {
                document.getElementById('activityTableBody').innerHTML = `
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="3" />
                                        <path d="M9 9h6M9 13h4" />
                                    </svg>
                                </div>
                                <div class="empty-title">No activity yet</div>
                                <div class="empty-desc">Recent activity from users will appear here.</div>
                            </div>
                        </td>
                    </tr>
                `;
            }
        } catch (error) {
            console.error('Error loading activity:', error);
        }
    }

    function renderActivity(activities) {
        const tbody = document.getElementById('activityTableBody');

        tbody.innerHTML = activities.map(activity => `
            <tr>
                <td style="color: var(--text);">${escapeHtml(activity.activity || '—')}</td>
                <td>${escapeHtml(activity.user || '—')}</td>
                <td>
                    <span class="status-dot ${activity.status === 'active' ? 'active' : (activity.status === 'pending' ? 'pending' : 'inactive')}"></span>
                    ${escapeHtml(activity.status ? activity.status.charAt(0).toUpperCase() + activity.status.slice(1) : 'Unknown')}
                </td>
                <td>${escapeHtml(activity.created_at || '—')}</td>
            </tr>
        `).join('');
    }

    // Refresh dashboard
    async function refreshDashboard() {
        showToast('info', 'Refreshing', 'Dashboard data is being updated...', 2000);
        await updateStatistics();
        await loadRecentActivity();
        setTimeout(() => {
            showToast('success', 'Refreshed', 'Dashboard data updated successfully.', 3000);
        }, 500);
    }

    // Initialize dashboard
    window.addEventListener('DOMContentLoaded', () => {
        loadRecentActivity();

        const shown = sessionStorage.getItem('gh_welcome');
        if (!shown) {
            setTimeout(() => showToast('success', 'Welcome back!', 'GuenHub Admin Panel loaded successfully.', 5000), 600);
            sessionStorage.setItem('gh_welcome', '1');
        }

        // Auto-refresh every 5 minutes
        setInterval(() => {
            updateStatistics();
            loadRecentActivity();
        }, 300000);
    });
</script>

@endsection