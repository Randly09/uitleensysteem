@extends('user.layouts.app')

@section('content')
<div class="lenen-page">
    <div id="lenen-menu" class="lenen-card menu-card">
        <button class="lenen-btn" onclick="showLenenScreen('geleend')">Geleend</button>
        <button class="lenen-btn" onclick="showLenenScreen('uitlenen')">Uitlenen</button>
    </div>

    <div id="lenen-form" class="lenen-card form-card hidden">
        <label>Zoeken</label>

        <div class="items-box">
            <div>Camera <button>+</button></div>
            <div>Tripod <button>+</button></div>
            <div>Microfoon <button>+</button></div>
        </div>

        <label>Datum & Tijd terugbrengen</label>

        <button id="lenen-action" class="lenen-submit">Lenen</button>
    </div>
</div>

<script>
    function showLenenScreen(type) {
        document.getElementById('lenen-menu').classList.add('hidden');
        document.getElementById('lenen-form').classList.remove('hidden');

        document.getElementById('lenen-action').innerText =
            type === 'geleend' ? 'Geleend' : 'Lenen';
    }
</script>
@endsection


