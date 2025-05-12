<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'My App')</title>
    @vite(['resources/js/donation.js', 'resources/css/donation.css'])
</head>
<body>
    <div id="app">
        @yield('content')
    </div>
</body>
</html>
