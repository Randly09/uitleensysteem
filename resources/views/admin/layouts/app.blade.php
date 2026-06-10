<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Admin - Uitleensysteem</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="admin-body">
    <div class="admin-page">
        <header class="admin-header">
            <div class="header-left">
                <img src="{{ asset('images/summa-logo.png') }}" alt="SUMMA">
            </div>

            <nav class="admin-nav">
                <a href="{{ route('admin.home') }}" class="{{ request()->routeIs('admin.home') ? 'active' : '' }}">
                    Home
                </a>

                <a href="{{ route('admin.materialen') }}" class="{{ request()->routeIs('admin.materialen') ? 'active' : '' }}">
                    Materialen
                </a>

                <a href="{{ route('admin.logboek') }}" class="{{ request()->routeIs('admin.logboek') ? 'active' : '' }}">
                    Logboek
                </a>

                <a href="{{ route('admin.retouren') }}" class="{{ request()->routeIs('admin.retouren') ? 'active' : '' }}">
                    Retours
                </a>
            </nav>

            <div class="header-right">
                <img src="{{ asset('images/samen-kun-je-meer.png') }}" alt="Samen kun je meer">
            </div>
        </header>

        <main class="admin-content">
            @yield('content')
        </main>
    </div>
</body>

</html>