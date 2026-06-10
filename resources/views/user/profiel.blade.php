@extends('user.layouts.app')

@section('content')
    <div class="profiel-page">

        <div class="profiel-card">

            <div class="profiel-avatar">
                👤
            </div>

            <h2 class="profiel-naam">
                Jan Jansen
            </h2>

            <div class="profiel-info">

                <div class="info-row">
                    <span>Studentnummer</span>
                    <strong>123456</strong>
                </div>

                <div class="info-row">
                    <span>Opleiding</span>
                    <strong>Software Developer</strong>
                </div>

                <div class="info-row">
                    <span>E-mail</span>
                    <strong>jan.jansen@student.summa.nl</strong>
                </div>

            </div>

            <button class="profiel-btn">
                Uitloggen
            </button>

        </div>

    </div>
@endsection