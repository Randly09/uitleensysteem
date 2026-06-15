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
        <form id="setFormElement" action="{{ route('admin.sets.store') }}" method="POST">
            @csrf
            <div id="setMethodField"></div>

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
                    <label>Lokaal</label>
                    <input type="text" name="lokaal" placeholder="Bijv. S2.45">
                </div>
            </div>

            <div class="form-row">
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
                                data-materiaal-id="{{ $materiaal->id }}"
                                onchange="toggleSetAmount(this)"
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

    <div class="materialen-list" id="materialContent">
        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Naam</th>
                    <th>Hoeveelheid</th>
                    <th>Beschikbaarheid</th>
                    <th>Lokaal</th>
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
                        <td>{{ $materiaal->lokaal ?? '-' }}</td>
                        <td>{{ $materiaal->conditie ?? '-' }}</td>
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
                    @php
                        $setMaterialen = $set->materialen->map(function ($materiaal) {
                            return [
                                'id' => $materiaal->id,
                                'aantal' => $materiaal->pivot->aantal,
                            ];
                        })->values();
                    @endphp

                    <tr class="materiaal-row" data-type="set" data-name="{{ strtolower($set->naam) }}">
                        <td>-</td>
                        <td>{{ $set->naam }}</td>
                        <td>{{ $set->hoeveelheid }}</td>
                        <td>{{ $set->hoeveelheid }}</td>
                        <td>{{ $set->lokaal ?? '-' }}</td>
                        <td>
                            @foreach($set->materialen as $materiaal)
                                {{ $materiaal->naam }} x{{ $materiaal->pivot->aantal }}<br>
                            @endforeach
                        </td>
                        <td>Set</td>
                        <td>
                            <button
                                type="button"
                                onclick="editSet(this)"
                                data-id="{{ $set->id }}"
                                data-naam="{{ $set->naam }}"
                                data-hoeveelheid="{{ $set->hoeveelheid }}"
                                data-lokaal="{{ $set->lokaal }}"
                                data-omschrijving="{{ $set->omschrijving }}"
                                data-materialen='@json($setMaterialen)'
                            >
                                Bekijken
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
   <div id="materialSkeleton">
        <x-table-skeleton :rows="5" :columns="5"  />
    </div>
</div>

<script>
    const fotoInput = document.getElementById('fotoInput');
    const fotoPreviewBox = document.getElementById('fotoPreviewBox');
    const fotoPreview = document.getElementById('fotoPreview');
    const fotoUploadLabel = document.getElementById('fotoUploadLabel');
    
    const materialItemsElement = document.getElementById('materialItemsData');
    const materialItems = JSON.parse(materialItemsElement ? materialItemsElement.value : '[]');

    document.addEventListener('DOMContentLoaded', function () {
        const skeleton = document.getElementById('materialSkeleton');
        const content = document.getElementById('materialContent');

        if (!skeleton || !content) {
            return;
        }

        content.style.display = 'none';
        skeleton.style.display = 'block';

        setTimeout(function () {
            skeleton.style.display = 'none';
            content.style.display = 'block';
        }, 600);
    });

    function showMaterialForm() {
        const materialForm = document.getElementById('materialForm');
        const setForm = document.getElementById('setForm');

        const shouldShow = !materialForm.classList.contains('show');

        setForm.classList.remove('show');
        resetMateriaalForm();

        materialForm.classList.toggle('show', shouldShow);
    }

    function showSetForm() {
        const setForm = document.getElementById('setForm');
        const materialForm = document.getElementById('materialForm');

        const shouldShow = !setForm.classList.contains('show');

        materialForm.classList.remove('show');
        resetSetForm();

        setForm.classList.toggle('show', shouldShow);
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

        form.querySelector('[name="naam"]').value = button.dataset.naam || '';
        form.querySelector('[name="hoeveelheid"]').value = button.dataset.hoeveelheid || '';
        form.querySelector('[name="lokaal"]').value = button.dataset.lokaal || '';
        form.querySelector('[name="conditie"]').value = button.dataset.conditie || '';
        form.querySelector('[name="opmerkingen"]').value = button.dataset.opmerkingen || '';

        scrollAdminContentTop();
    }

    function editSet(button) {
        const setFormCard = document.getElementById('setForm');
        const materialForm = document.getElementById('materialForm');
        const form = document.getElementById('setFormElement');
        const methodField = document.getElementById('setMethodField');

        materialForm.classList.remove('show');
        setFormCard.classList.add('show');

        resetSetForm();

        form.action = `/admin/sets/${button.dataset.id}`;
        methodField.innerHTML = '@method("PUT")';

        form.querySelector('[name="set_naam"]').value = button.dataset.naam || '';
        form.querySelector('[name="hoeveelheid"]').value = button.dataset.hoeveelheid || '';
        form.querySelector('[name="lokaal"]').value = button.dataset.lokaal || '';
        form.querySelector('[name="omschrijving"]').value = button.dataset.omschrijving || '';

        const materialen = JSON.parse(button.dataset.materialen || '[]');

        materialen.forEach(function (materiaal) {
            const checkbox = form.querySelector(`[data-materiaal-id="${materiaal.id}"]`);

            if (!checkbox) {
                return;
            }

            checkbox.checked = true;

            const item = checkbox.closest('.set-material-item');
            const amountInput = item.querySelector('.set-amount-input');

            amountInput.disabled = false;
            amountInput.required = true;
            amountInput.value = materiaal.aantal || 1;
        });

        scrollAdminContentTop();
    }

    function resetMateriaalForm() {
        const form = document.getElementById('materiaalForm');
        const methodField = document.getElementById('methodField');

        form.action = "{{ route('admin.materialen.store') }}";
        methodField.innerHTML = '';
        form.reset();
        removeFoto();
    }

    function resetSetForm() {
        const form = document.getElementById('setFormElement');
        const methodField = document.getElementById('setMethodField');

        form.action = "{{ route('admin.sets.store') }}";
        methodField.innerHTML = '';
        form.reset();

        document.querySelectorAll('.set-material-item input[type="checkbox"]').forEach(function (checkbox) {
            checkbox.checked = false;
        });

        document.querySelectorAll('.set-amount-input').forEach(function (input) {
            input.disabled = true;
            input.required = false;
            input.value = 1;
        });
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

    function scrollAdminContentTop() {
        const adminContent = document.querySelector('.admin-content');

        if (adminContent) {
            adminContent.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    }
</script>
@endsection