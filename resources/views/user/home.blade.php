@extends('user.layouts.app')

@section('content')
<div class="user-home-page">

    <div class="user-home-card">
        <h1>Welkom bij de gebruikerszijde</h1>

        <p>
            Dit is de gebruikerskant van het uitleensysteem. Hier kan een student materialen lenen
            en het eigen profiel bekijken.
        </p>

        <p>
            Via de knop hieronder kun je terug naar de adminzijde van het prototype.
        </p>

        <a href="{{ route('admin.home') }}" class="go-admin-btn">
            Naar adminzijde
        </a>
    </div>

</div>
@endsection