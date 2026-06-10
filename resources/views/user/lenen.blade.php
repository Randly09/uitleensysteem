@extends('user.layouts.app')

@section('content')
    <div class="lenen-page">

        {{-- MENU --}}
        <div id="lenen-menu" class="lenen-card menu-card">

            <button class="lenen-btn" data-type="lenen">
                Lenen
            </button>

            <button class="lenen-btn" data-type="terugbrengen">
                Terugbrengen
            </button>

        </div>

        {{-- LENEN FORM --}}
        <div id="lenen-form" class="lenen-card form-card hidden">

            <label>Zoeken</label>

            <div class="items-box">
                <div>
                    Camera
                    <button type="button">+</button>
                </div>

                <div>
                    Tripod
                    <button type="button">+</button>
                </div>

                <div>
                    Microfoon
                    <button type="button">+</button>
                </div>
            </div>

            <label>Datum & Tijd terugbrengen</label>

            <div class="date-box">
                <input type="datetime-local">
            </div>

            <button id="lenen-submit-btn" class="lenen-submit">
                Lenen
            </button>

        </div>

        {{-- TERUGBRENGEN FORM --}}
        <div id="terugbrengen-form" class="lenen-card form-card hidden">

            <label>Geleende materialen</label>

            <div class="items-box">

                <div>
                    Camera
                    <input type="checkbox">
                </div>

                <div>
                    Tripod
                    <input type="checkbox">
                </div>

                <div>
                    Microfoon
                    <input type="checkbox">
                </div>

            </div>

            <button id="terugbrengen-submit-btn" class="lenen-submit">
                Terugbrengen
            </button>

        </div>

        {{-- LENEN BEVESTIGING --}}
        <div id="lenen-bevestiging" class="lenen-card confirmation-card hidden">

            <div class="confirmation-content">

                <div class="success-icon">
                    ✓
                </div>

                <h2>Lening bevestigd</h2>

                <p>
                    Je aanvraag is succesvol verwerkt.
                </p>

            </div>

            <button id="lenen-terug-overzicht" class="lenen-submit">
                Terug 
            </button>

        </div>

        {{-- TERUGBRENGEN BEVESTIGING --}}
        <div id="terugbrengen-bevestiging" class="lenen-card confirmation-card hidden">

            <div class="confirmation-content">

                <div class="success-icon">
                    ✓
                </div>

                <h2>Materiaal teruggebracht</h2>

                <p>
                    Het materiaal is succesvol teruggebracht.
                </p>

            </div>

            <button id="terugbrengen-terug-overzicht" class="lenen-submit">
                Terug
            </button>

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const menu = document.getElementById('lenen-menu');

            const lenenForm = document.getElementById('lenen-form');
            const terugbrengenForm = document.getElementById('terugbrengen-form');

            const lenenBevestiging = document.getElementById('lenen-bevestiging');
            const terugbrengenBevestiging = document.getElementById('terugbrengen-bevestiging');

            const lenenSubmitBtn = document.getElementById('lenen-submit-btn');
            const terugbrengenSubmitBtn = document.getElementById('terugbrengen-submit-btn');

            const lenenTerugBtn = document.getElementById('lenen-terug-overzicht');
            const terugbrengenTerugBtn = document.getElementById('terugbrengen-terug-overzicht');

            function resetScreens() {
                lenenForm.classList.add('hidden');
                terugbrengenForm.classList.add('hidden');
                lenenBevestiging.classList.add('hidden');
                terugbrengenBevestiging.classList.add('hidden');
            }

            document.querySelectorAll('.lenen-btn').forEach(button => {

                button.addEventListener('click', () => {

                    resetScreens();

                    menu.classList.add('hidden');

                    if (button.dataset.type === 'lenen') {
                        lenenForm.classList.remove('hidden');
                    } else {
                        terugbrengenForm.classList.remove('hidden');
                    }

                });

            });

            lenenSubmitBtn.addEventListener('click', () => {

                lenenForm.classList.add('hidden');
                lenenBevestiging.classList.remove('hidden');

            });

            terugbrengenSubmitBtn.addEventListener('click', () => {

                terugbrengenForm.classList.add('hidden');
                terugbrengenBevestiging.classList.remove('hidden');

            });

            lenenTerugBtn.addEventListener('click', () => {

                resetScreens();
                menu.classList.remove('hidden');

            });

            terugbrengenTerugBtn.addEventListener('click', () => {

                resetScreens();
                menu.classList.remove('hidden');

            });

        });
    </script>
@endsection