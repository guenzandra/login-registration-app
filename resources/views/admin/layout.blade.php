<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin Dashboard - {{ config('app.name') }}</title>
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  <script src="{{ asset('js/admin.js') }}" defer></script>
</head>

<body>
  @include('admin.nav')

  <div class="content">
    @yield('content')
  </div>
</body>

</html>