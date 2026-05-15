<div class="nav">
  <!-- Left panel for navigation -->
  <div class="leftpanel">
    <ul>
      <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
      <li><a href="{{ route('admin.accounts') }}">User Accounts</a></li>
      <li><a href="{{ route('admin.settings') }}">Settings</a></li>
    </ul>
  </div>

  <!-- Top navigation bar -->
  <div class="topnav">
    <div class="topnav-left">
      <span id="time"></span>
    </div>
    <div class="topnav-center">
      <span id="date"></span>
    </div>
    <div class="topnav-right">
      <a href="{{ route('logout') }}">Logout</a>
    </div>
  </div>
</div>