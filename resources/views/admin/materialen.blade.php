@extends('admin.layouts.app')

@section('content')
<div class="materialen-page">

    <div class="materialen-top">
        <button class="add-material-btn" onclick="showMaterialForm()">+ Materiaal toevoegen</button>
        <button class="add-set-btn" onclick="showSetForm()">+ Set toevoegen</button>
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
                    <input type="number" name="hoeveelheid" min="0" required>
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
                    <label>Foto</label>

                    <input id="fotoInput" class="foto-input" type="file" name="foto" accept="image/*" onchange="previewFoto(event)">

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
        <form action="{{ route('admin.sets.store') }}" method="POST">
            @csrf

            <div class="form-row">
                <div>
                    <label>Naam set</label>
                    <input type="text" name="set_naam" placeholder="Bijv. Podcast set" required>
                </div>

                <div>
                    <label>Hoeveelheid sets</label>
                    <input type="number" name="hoeveelheid" value="1" min="1" required>
                </div>

                <div>
                    <label>Zoek materiaal</label>
                    <input type="text" id="setSearchInput" placeholder="Zoeken naar materiaal" onkeyup="filterSetMaterialen()">
                </div>
            </div>

            <label>Materialen toevoegen aan set</label>

            <div class="set-material-list">
                @foreach($materialen as $materiaal)
                    <div class="set-material-item" data-name="{{ strtolower($materiaal->naam) }}">
                        <div class="set-material-info">
                            <input
                                type="checkbox"
                                name="materialen[{{ $loop->index }}][id]"
                                value="{{ $materiaal->id }}"
                                onchange="toggleSetAmount(this)"
                                @if($materiaal->beschikbaarheid < 1) disabled @endif
                            >

                            <span>
                                <strong>{{ $materiaal->naam }}</strong>
                                <small>Beschikbaar: {{ $materiaal->beschikbaarheid }}</small>
                            </span>
                        </div>

                        <input
                            class="set-amount-input"
                            type="number"
                            name="materialen[{{ $loop->index }}][aantal]"
                            value="1"
                            min="1"
                            max="{{ max(1, $materiaal->beschikbaarheid) }}"
                            disabled
                        >
                    </div>
                @endforeach
            </div>

            <label>Omschrijving</label>
            <textarea name="omschrijving" placeholder="Bijv. Set voor podcastopnames met camera, microfoon en tripod"></textarea>

            <button class="confirm-btn" type="submit">Set opslaan</button>
        </form>
    </div>

    <form class="search-box" onsubmit="filterMaterialen(event)">
        <input id="materiaalSearchInput" type="text" placeholder="Zoeken">

        <select id="materiaalTypeFilter">
            <option value="all">Alles</option>
            <option value="materiaal">Materiaal</option>
            <option value="set">Set</option>
        </select>

        <button type="submit">Zoeken</button>
    </form>

    <div class="materialen-list">
        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Naam</th>
                    <th>Hoeveelheid</th>
                    <th>Beschikbaarheid</th>
                    <th>Conditie / Inhoud</th>
                    <th>Type</th>
                    <th>Acties</th>
                </tr>
            </thead>

            <tbody>
                @foreach($materialen as $materiaal)
                    <tr class="materiaal-row" data-type="materiaal" data-name="{{ strtolower($materiaal->naam) }}">
                        <td>
                            @if($materiaal->foto_path)
                                <img src="{{ asset($materiaal->foto_path) }}" class="materiaal-img">
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $materiaal->naam }}</td>
                        <td>{{ $materiaal->hoeveelheid }}</td>
                        <td>{{ $materiaal->beschikbaarheid }}</td>
                        <td>{{ $materiaal->conditie }}</td>
                        <td>Materiaal</td>
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

                @foreach($sets as $set)
                    <tr class="materiaal-row" data-type="set" data-name="{{ strtolower($set->naam) }}">
                        <td>-</td>
                        <td>{{ $set->naam }}</td>
                        <td>{{ $set->hoeveelheid }}</td>
                        <td>{{ $set->hoeveelheid }}</td>
                        <td>
                            @foreach($set->materialen as $materiaal)
                                {{ $materiaal->naam }} x{{ $materiaal->pivot->aantal }}<br>
                            @endforeach
                        </td>
                        <td>Set</td>
                        <td>
                            <button type="button">Bekijken</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
        resetMateriaalForm();
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

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function resetMateriaalForm() {
        const form = document.getElementById('materiaalForm');
        const methodField = document.getElementById('methodField');

        form.action = "{{ route('admin.materialen.store') }}";
        methodField.innerHTML = '';
        form.reset();
        removeFoto();
    }

    function toggleSetAmount(checkbox) {
        const item = checkbox.closest('.set-material-item');
        const amountInput = item.querySelector('.set-amount-input');

        if (checkbox.checked) {
            amountInput.disabled = false;
            amountInput.required = true;
        } else {
            amountInput.disabled = true;
            amountInput.required = false;
            amountInput.value = 1;
        }
    }

    function filterSetMaterialen() {
        const searchValue = document.getElementById('setSearchInput').value.toLowerCase();
        const items = document.querySelectorAll('.set-material-item');

        items.forEach(item => {
            const name = item.dataset.name;
            item.style.display = name.includes(searchValue) ? 'flex' : 'none';
        });
    }

    function filterMaterialen(event) {
        event.preventDefault();

        const searchValue = document.getElementById('materiaalSearchInput').value.toLowerCase().trim();
        const typeValue = document.getElementById('materiaalTypeFilter').value;
        const rows = document.querySelectorAll('.materiaal-row');

        rows.forEach(row => {
            const name = row.dataset.name;
            const type = row.dataset.type;

            const matchesSearch = searchValue === '' || name.includes(searchValue);
            const matchesType = typeValue === 'all' || type === typeValue;

            row.style.display = matchesSearch && matchesType ? '' : 'none';
        });
    }
</script>
@endsection