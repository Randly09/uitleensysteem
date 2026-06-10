@extends('admin.layouts.app')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=
    , initial-scale=1.0">
    <title>Admin - Uitleensysteem</title>
</head>
<body>
   
</body>
</html>
@section('content')
<div class="admin-home-page">

    <div class="admin-home-card">
        <h1>Welkom bij de adminzijde van het prototype</h1>

        <p>
            Dit is de beheeromgeving van het uitleensysteem. Hier kan de beheerder materialen beheren,
            het logboek bekijken en retouren controleren.
        </p>

        <p>
            Via de knop hieronder kun je naar de gebruikerszijde van het prototype gaan.
        </p>

        <a href="{{ route('user.home') }}" class="go-user-btn">
            Naar gebruikerszijde
        </a>
    </div>

</div>
@endsection