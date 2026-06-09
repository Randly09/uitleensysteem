<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Student - Uitleensysteem</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="user-body">
    <div class="user-phone">
        <header class="user-header">
            <img class="user-logo" src="{{ asset('images/summa-logo.png') }}" alt="SUMMA">
            <img class="user-badge" src="{{ asset('images/samen-kun-je-meer.png') }}" alt="Samen kun je meer">
        </header>

        <main class="user-content">
            @yield('content')
        </main>

        <footer class="user-footer">
            <a href="{{ route('user.home') }}" class="footer-icon">
                <img src="{{ asset('images/home-icon.png') }}" alt="Home">
            </a>

            <a href="{{ route('user.lenen') }}" class="footer-icon active">
                <img src="{{ asset('images/logboek-icon.png') }}" alt="Lenen">
            </a>

            <a href="{{ route('user.profiel') }}" class="footer-icon">
                <img src="{{ asset('images/profile-icon.png') }}" alt="Profiel">
            </a>
        </footer>
    </div>
</body>
</html>