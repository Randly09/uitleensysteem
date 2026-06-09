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
            <div class="logo">SUMMA</div>

            <nav class="admin-nav">
                <a href="{{ route('admin.materialen') }}">Materialen</a>
                <a href="{{ route('admin.logboek') }}">Logboek</a>
                <a href="{{ route('admin.home') }}">Home</a>
                <a href="{{ route('admin.retouren') }}">Retours</a>
            </nav>

            <div class="summa-badge">
                samen<br>kun je<br>meer
            </div>
        </header>

        <main class="admin-content">
            @yield('content')
        </main>

        <footer class="admin-footer"></footer>
    </div>
</body>
</html>