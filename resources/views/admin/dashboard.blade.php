<!-- resources/views/admin/dashboard.blade.php -->
@extends('admin.layout')

@section('content')
<div class="dashboard-stats">
    <div class="stat-card">
        <h3>Total Registered Accounts</h3>
        <!-- <div class="stat-number">{{ $registeredAccounts ?? 0 }}</div> -->
        <div class="stat-label">Active users in the system</div>
    </div>

    <div class="stat-card">
        <h3>New This Month</h3>
        <!-- <div class="stat-number">{{ $newAccountsThisMonth ?? 0 }}</div> -->
        <div class="stat-label">Registered in the last 30 days</div>
    </div>

    <div class="stat-card">
        <h3>Active Sessions</h3>
        <!-- <div class="stat-number">{{ $activeSessions ?? 0 }}</div> -->
        <div class="stat-label">Currently logged in users</div>
    </div>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Recent Activity</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="3" style="text-align: center; padding: 40px;">
                    Welcome to your admin dashboard!
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection