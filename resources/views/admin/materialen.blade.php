@extends('admin.layouts.app')

@section('content')
<div class="materialen-page">

    <button class="add-material-btn" onclick="openMaterialModal()">
        + Materiaal toevoegen
    </button>

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

    <div id="materialModal" class="modal-overlay">
        <div class="modal-card">
            <button class="modal-close" onclick="closeMaterialModal()">×</button>

            <h2>Materiaal toevoegen</h2>

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
                </div>

                <div class="form-row">
                    <div>
                        <label>Beschikbaarheid</label>
                        <input type="number" name="beschikbaarheid" required>
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
                        <input type="file" name="foto">
                    </div>
                </div>

                <label>Opmerkingen</label>
                <textarea name="opmerkingen"></textarea>

                <button class="confirm-btn" type="submit">Bevestigen</button>
            </form>
        </div>
    </div>

</div>

<script>
    function openMaterialModal() {
        document.getElementById('materialModal').classList.add('show');
    }

    function closeMaterialModal() {
        document.getElementById('materialModal').classList.remove('show');
    }
</script>
@endsection