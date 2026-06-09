@extends('user.layouts.app')

@section('content')
    <div class="lenen-page">

        <!-- MENU -->
        <div id="lenen-menu" class="lenen-card menu-card">
            <button class="lenen-btn" data-type="geleend">Lenen</button>
            <button class="lenen-btn" data-type="uitlenen">Terugbrengen</button>
        </div>

        <!-- FORM -->
        <div id="lenen-form" class="lenen-card form-card hidden">
            <label>Zoeken</label>

            <div class="items-box">
                <div>Camera <button>+</button></div>
                <div>Tripod <button>+</button></div>
                <div>Microfoon <button>+</button></div>
            </div>

            <label>Datum & Tijd terugbrengen</label>

            <button id="lenen-action" class="lenen-submit">
                Lenen
            </button>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const menu = document.getElementById('lenen-menu');
            const form = document.getElementById('lenen-form');
            const actionBtn = document.getElementById('lenen-action');

            document.querySelectorAll('.lenen-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const type = btn.dataset.type;

                    menu.classList.add('hidden');
                    form.classList.remove('hidden');

                    actionBtn.innerText = type === 'Lenen'
                        ? 'Geleend'
                        : 'Lenen';
                });
            });
        });
    </script>
@endsection