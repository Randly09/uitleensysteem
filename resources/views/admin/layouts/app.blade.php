<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Admin - Uitleensysteem</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header>
        <div class="logo">SUMMA</div>

        <nav>
            <a href="{{ route('admin.materialen') }}">Materialen</a>
            <a href="{{ route('admin.logboek') }}">Logboek</a>
            <a href="{{ route('admin.home') }}">Home</a>
            <a href="{{ route('admin.retouren') }}">Retours</a>
        </nav>

        <div class="badge">samen<br>kun je<br>meer</div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer></footer>
</body>
</html>