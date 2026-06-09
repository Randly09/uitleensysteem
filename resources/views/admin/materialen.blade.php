@extends('admin.layouts.app')

@section('content')
<div class="materialen-page">

    <div class="materialen-top">
        <button class="add-material-btn" onclick="toggleMaterialForm()">
            + Materiaal toevoegen
        </button>
    </div>

    <div id="materialForm" class="material-form-card">
        <form action="{{ route('admin.materialen.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

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
                    <label>Beschikbaarheid</label>
                    <input type="number" name="beschikbaarheid" required>
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label>Lokaal</label>
                    <input type="text" name="lokaal">
                </div>

                <div>
                    <label>Conditie</label>
                    <input type="text" name="conditie">
                </div>

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

    <div class="materialen-center">

        <form class="search-box">
            <input type="text" placeholder="Zoeken">
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
                            <td>
                                <button type="button">Bekijken</button>
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

    function toggleMaterialForm() {
        document.getElementById('materialForm').classList.toggle('show');
    }

    function previewFoto(event) {
        const file = event.target.files[0];

        if (!file) {
            return;
        }

        fotoPreview.src = URL.createObjectURL(file);
        fotoPreviewBox.style.display = 'block';
        fotoUploadLabel.style.display = 'none';
        fotoInput.disabled = true;
    }

    function removeFoto() {
        fotoInput.disabled = false;
        fotoInput.value = '';
        fotoPreview.src = '';
        fotoPreviewBox.style.display = 'none';
        fotoUploadLabel.style.display = 'flex';
    }
</script>
@endsection