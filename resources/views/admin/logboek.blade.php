@extends('admin.layouts.app')

@section('content')
<div class="logboek-page">

    <form class="logboek-search-box" onsubmit="filterLogboek(event)">
        <input
            id="logboekSearchInput"
            type="text"
            placeholder="Zoeken op PS-nummer of materiaal"
            oninput="filterLogboek()"
        >

        <select id="terugFilter" onchange="filterLogboek()">
            <option value="all">Alles</option>
            <option value="terug">Terug</option>
            <option value="niet-terug">Niet terug</option>
        </select>

        <select id="sortFilter" onchange="filterLogboek()">
            <option value="updated">Laatst gewijzigd</option>
            <option value="recent">Inleverdatum: recent eerst</option>
            <option value="oud">Inleverdatum: oud eerst</option>
            <option value="hoeveelheid-hoog">Hoeveelheid: hoog-laag</option>
            <option value="hoeveelheid-laag">Hoeveelheid: laag-hoog</option>
        </select>

        <button type="submit">Zoeken</button>
    </form>

    <div class="logboek-list">
        <table id="logboekTable">
            <thead>
                <tr>
                    <th>Materiaal / Set</th>
                    <th>PS-nummer</th>
                    <th>Inleverdatum</th>
                    <th>Hoeveelheid</th>
                    <th>Conditie</th>
                    <th>Terug</th>
                </tr>
            </thead>

            <tbody>
                @foreach($logboeken as $logboek)
                    <tr
                        class="logboek-row"
                        data-name="{{ strtolower($logboek->item_naam ?? '') }}"
                        data-ps="{{ strtolower($logboek->psnummer ?? '') }}"
                        data-terug="{{ $logboek->terug ? 'terug' : 'niet-terug' }}"
                        data-date="{{ $logboek->inleverdatum }}"
                        data-updated="{{ $logboek->updated_at }}"
                        data-hoeveelheid="{{ $logboek->hoeveelheid }}"
                    >
                        <td>{{ $logboek->item_naam }}</td>

                        <td>{{ $logboek->psnummer ?? '-' }}</td>

                        <td>{{ \Carbon\Carbon::parse($logboek->inleverdatum)->format('d-m-Y') }}</td>

                        <td>{{ $logboek->hoeveelheid }}</td>

                        <td>
                            @if($logboek->item_type === 'materiaal')
                                {{ $logboek->conditie ?? '-' }}
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            <form action="{{ route('admin.logboek.terug', $logboek->id) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <button class="terug-btn {{ $logboek->terug ? 'is-terug' : 'niet-terug' }}" type="submit">
                                    {{ $logboek->terug ? '✓' : '✕' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="logboek-pagination">
            <button type="button" id="prevPageBtn" onclick="changeLogboekPage(-1)">‹</button>
            <span id="pageInfo">Pagina 1 van 1</span>
            <button type="button" id="nextPageBtn" onclick="changeLogboekPage(1)">›</button>
        </div>
    </div>

</div>

<script>
    let currentPage = 1;
    const rowsPerPage = 10;

    function filterLogboek(event = null, resetPage = true) {
        if (event) {
            event.preventDefault();
        }

        if (resetPage) {
            currentPage = 1;
        }

        const searchValue = document.getElementById('logboekSearchInput').value.toLowerCase().trim();
        const terugValue = document.getElementById('terugFilter').value;
        const sortValue = document.getElementById('sortFilter').value;

        const tbody = document.querySelector('#logboekTable tbody');
        const rows = Array.from(document.querySelectorAll('.logboek-row'));

        let filteredRows = rows.filter(row => {
            const name = row.dataset.name;
            const ps = row.dataset.ps;
            const terug = row.dataset.terug;

            const matchesSearch =
                searchValue === '' ||
                name.includes(searchValue) ||
                ps.includes(searchValue);

            const matchesTerug =
                terugValue === 'all' ||
                terug === terugValue;

            return matchesSearch && matchesTerug;
        });

        filteredRows.sort((a, b) => {
            if (sortValue === 'updated') {
                return new Date(b.dataset.updated) - new Date(a.dataset.updated);
            }

            if (sortValue === 'recent') {
                return new Date(b.dataset.date) - new Date(a.dataset.date);
            }

            if (sortValue === 'oud') {
                return new Date(a.dataset.date) - new Date(b.dataset.date);
            }

            if (sortValue === 'hoeveelheid-hoog') {
                return Number(b.dataset.hoeveelheid) - Number(a.dataset.hoeveelheid);
            }

            if (sortValue === 'hoeveelheid-laag') {
                return Number(a.dataset.hoeveelheid) - Number(b.dataset.hoeveelheid);
            }

            return 0;
        });

        rows.forEach(row => {
            row.style.display = 'none';
        });

        filteredRows.forEach(row => {
            tbody.appendChild(row);
        });

        const totalPages = Math.max(1, Math.ceil(filteredRows.length / rowsPerPage));

        if (currentPage > totalPages) {
            currentPage = totalPages;
        }

        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        filteredRows.slice(start, end).forEach(row => {
            row.style.display = '';
        });

        updatePagination(totalPages, filteredRows.length);
    }

    function changeLogboekPage(direction) {
        const searchValue = document.getElementById('logboekSearchInput').value.toLowerCase().trim();
        const terugValue = document.getElementById('terugFilter').value;
        const rows = Array.from(document.querySelectorAll('.logboek-row'));

        const filteredRows = rows.filter(row => {
            const name = row.dataset.name;
            const ps = row.dataset.ps;
            const terug = row.dataset.terug;

            const matchesSearch =
                searchValue === '' ||
                name.includes(searchValue) ||
                ps.includes(searchValue);

            const matchesTerug =
                terugValue === 'all' ||
                terug === terugValue;

            return matchesSearch && matchesTerug;
        });

        const totalPages = Math.max(1, Math.ceil(filteredRows.length / rowsPerPage));

        currentPage += direction;

        if (currentPage < 1) {
            currentPage = 1;
        }

        if (currentPage > totalPages) {
            currentPage = totalPages;
        }

        filterLogboek(null, false);
    }

    function updatePagination(totalPages, totalRows) {
        const pageInfo = document.getElementById('pageInfo');
        const prevBtn = document.getElementById('prevPageBtn');
        const nextBtn = document.getElementById('nextPageBtn');

        pageInfo.textContent = `Pagina ${currentPage} van ${totalPages}`;

        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages || totalRows === 0;
    }

    filterLogboek(null, false);
</script>
@endsection