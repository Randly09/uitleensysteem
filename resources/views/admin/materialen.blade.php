@extends('admin.layouts.app')

@section('content')
<div class="materialen-page">

    <div class="materialen-top">
        <button class="add-material-btn" onclick="showMaterialForm()">
            + Materiaal toevoegen
        </button>

        <button class="add-set-btn" onclick="showSetForm()">
            + Set toevoegen
        </button>
    </div>

    <div id="materialForm" class="material-form-card">
        <form id="materiaalForm" action="{{ route('admin.materialen.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="methodField"></div>

            <div class="form-row">
                <div>
                    <label>Naam</label>
                    <input type="text" name="naam" required>
                </div>

                <div>
                    <label>Hoeveelheid</label>
                    <input type="number" name="hoeveelheid" required>
                </div>

                <div>
                    <label>Lokaal</label>
                    <input type="text" name="lokaal">
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label>Conditie</label>
                    <input type="text" name="conditie">
                </div>

                <div>
                    <label>Aantal met andere conditie</label>
                    <input type="number" name="split_aantal" placeholder="Bijv. 2">
                </div>

                <div>
                    <label>Nieuwe conditie</label>
                    <input type="text" name="split_conditie" placeholder="Bijv. Slecht">
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label>Foto</label>

                    <input
                        id="fotoInput"
                        class="foto-input"
                        type="file"
                        name="foto"
                        accept="image/*"
                        onchange="previewFoto(event)"
                    >

                    <label id="fotoUploadLabel" for="fotoInput" class="foto-upload-label">
                        Bestand kiezen
                    </label>

                    <div id="fotoPreviewBox" class="foto-preview-box">
                        <button type="button" class="remove-foto-btn" onclick="removeFoto()">×</button>
                        <img id="fotoPreview" src="" alt="Preview">
                    </div>
                </div>
            </div>

            <label>Opmerkingen</label>
            <textarea name="opmerkingen"></textarea>

            <button class="confirm-btn" type="submit">Bevestigen</button>
        </form>
    </div>

    <div id="setForm" class="material-form-card">
        <form action="#" method="POST">
            @csrf

            <div class="form-row">
                <div>
                    <label>Naam set</label>
                    <input type="text" name="set_naam" placeholder="Bijv. Podcast set">
                </div>

                <div>
                    <label>Zoek materiaal</label>
                    <input type="text" placeholder="Zoeken naar materiaal">
                </div>
            </div>

            <label>Materialen toevoegen aan set</label>

            <div class="set-material-list">
                @foreach($materialen as $materiaal)
                    <label class="set-material-item">
                        <input type="checkbox" name="materialen[]" value="{{ $materiaal->id }}">
                        <span>{{ $materiaal->naam }} - beschikbaar: {{ $materiaal->beschikbaarheid }}</span>
                    </label>
                @endforeach
            </div>

            <label>Omschrijving</label>
            <textarea name="omschrijving" placeholder="Bijv. Set voor podcastopnames met camera, microfoon en tripod"></textarea>

            <button class="confirm-btn" type="submit">Set opslaan</button>
        </form>
    </div>

    <div class="materialen-center">

        <form class="search-box">
            <input type="text" placeholder="Zoeken">
            <select>
                <option>Filter op materiaal</option>
                <option>Filter op set</option>
            </select>
            <button type="button">Zoeken</button>
        </form>

        <div class="materialen-list">
            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Naam</th>
                        <th>Hoeveelheid</th>
                        <th>Beschikbaarheid</th>
                        <th>Conditie</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materialen as $materiaal)
                        <tr>
                            <td>
                                @if($materiaal->foto_path)
                                    <img src="{{ asset($materiaal->foto_path) }}" class="materiaal-img">
                                @endif
                            </td>
                            <td>{{ $materiaal->naam }}</td>
                            <td>{{ $materiaal->hoeveelheid }}</td>
                            <td>{{ $materiaal->beschikbaarheid }}</td>
                            <td>{{ $materiaal->conditie }}</td>
                            <td>
                                <button
                                    type="button"
                                    onclick="editMateriaal(this)"
                                    data-id="{{ $materiaal->id }}"
                                    data-naam="{{ $materiaal->naam }}"
                                    data-hoeveelheid="{{ $materiaal->hoeveelheid }}"
                                    data-lokaal="{{ $materiaal->lokaal }}"
                                    data-conditie="{{ $materiaal->conditie }}"
                                    data-opmerkingen="{{ $materiaal->opmerkingen }}"
                                >
                                    Bekijken
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
    const fotoInput = document.getElementById('fotoInput');
    const fotoPreviewBox = document.getElementById('fotoPreviewBox');
    const fotoPreview = document.getElementById('fotoPreview');
    const fotoUploadLabel = document.getElementById('fotoUploadLabel');

    function showMaterialForm() {
        document.getElementById('materialForm').classList.toggle('show');
        document.getElementById('setForm').classList.remove('show');
    }

    function showSetForm() {
        document.getElementById('setForm').classList.toggle('show');
        document.getElementById('materialForm').classList.remove('show');
    }

    function previewFoto(event) {
        const file = event.target.files[0];

        if (!file) return;

        fotoPreview.src = URL.createObjectURL(file);
        fotoPreviewBox.style.display = 'block';
        fotoUploadLabel.style.display = 'none';
        fotoUploadLabel.style.pointerEvents = 'none';
    }

    function removeFoto() {
        fotoInput.value = '';
        fotoPreview.src = '';
        fotoPreviewBox.style.display = 'none';
        fotoUploadLabel.style.display = 'flex';
        fotoUploadLabel.style.pointerEvents = 'auto';
    }

    function editMateriaal(button) {
        const formCard = document.getElementById('materialForm');
        const setForm = document.getElementById('setForm');
        const form = document.getElementById('materiaalForm');
        const methodField = document.getElementById('methodField');

        formCard.classList.add('show');
        setForm.classList.remove('show');

        form.action = `/admin/materialen/${button.dataset.id}`;
        methodField.innerHTML = '@method("PUT")';

        form.querySelector('[name="naam"]').value = button.dataset.naam;
        form.querySelector('[name="hoeveelheid"]').value = button.dataset.hoeveelheid;
        form.querySelector('[name="lokaal"]').value = button.dataset.lokaal;
        form.querySelector('[name="conditie"]').value = button.dataset.conditie;
        form.querySelector('[name="opmerkingen"]').value = button.dataset.opmerkingen;
        form.querySelector('[name="split_aantal"]').value = '';
        form.querySelector('[name="split_conditie"]').value = '';

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>
@endsection