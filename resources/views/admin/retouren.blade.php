@extends('admin.layouts.app')

@section('content')
@php
    $retourItemsData = collect($retourItems ?? [])->values();
@endphp

<div class="retour-page">

    <div id="retourListSection">
        <form class="retour-search-box" onsubmit="filterRetours(event)">
            <input
                id="retourSearchInput"
                type="text"
                placeholder="Zoeken op PS-nummer of materiaal"
                oninput="filterRetours()"
            >

            <button type="submit">Zoeken</button>
        </form>

        <div id="retourSkeleton">
            <x-table-skeleton :rows="5" />
        </div>

        <div id="retourContent" class="retour-list">
            <table id="retourTable">
                <thead>
                    <tr>
                        <th>Materiaal / Set</th>
                        <th>PS-nummer</th>
                        <th>Inleverdatum / tijd</th>
                        <th>Hoeveelheid</th>
                        <th>Actie</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($retours as $retour)
                        @php
                            $user = $retour->users->first();
                            $psnummer = $user?->Psnummer;
                            $userId = $user?->id;
                        @endphp

                        <tr
                            class="retour-row"
                            data-name="{{ strtolower($retour->item_naam ?? '') }}"
                            data-ps="{{ strtolower($psnummer ?? '') }}"
                        >
                            <td>{{ $retour->item_naam }}</td>

                            <td>{{ $psnummer ?: '-' }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($retour->retour_datum)->format('d-m-Y H:i') }}
                            </td>

                            <td>{{ $retour->aantal }}</td>

                            <td>
                                <button
                                    type="button"
                                    class="retour-btn"
                                    data-user-id="{{ $userId }}"
                                    data-psnummer="{{ $psnummer }}"
                                    data-retour-id="{{ $retour->id }}"
                                    onclick="openRetourFormFromButton(this)"
                                >
                                    Retour
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-row">
                                Er zijn geen retouren voor deze week.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="retourFormSection" class="retour-form-section">
        <button type="button" class="back-to-list-btn" onclick="backToRetourList()">
            Terug
        </button>

        <div class="retour-form-card">
            <form action="{{ route('admin.retouren.process') }}" method="POST">
                @csrf

                <label>PS-nummer</label>
                <input id="retourPsnummerInput" type="text" readonly>

                <label>Materialen / sets die worden teruggebracht</label>
                <div id="retourCheckboxList" class="retour-checkbox-list"></div>

                <label>Conditie</label>
                <select name="conditie" required>
                    <option value="Goed">Goed</option>
                    <option value="Verslechterd">Verslechterd</option>
                    <option value="Kapot">Kapot</option>
                </select>

                <label>Opmerking</label>
                <textarea
                    name="opmerking"
                    placeholder="Bijv. kabel mist, product beschadigd, verpakking kapot..."
                ></textarea>

                <button type="submit" class="confirm-retour-btn">
                    Retourneren
                </button>
            </form>
        </div>
    </div>

</div>

<script type="application/json" id="retourItemsData">
{!! json_encode($retourItemsData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
</script>

<script>
    const retourItemsElement = document.getElementById('retourItemsData');
    const retourItems = JSON.parse(retourItemsElement.textContent);

    document.addEventListener('DOMContentLoaded', function () {
        const skeleton = document.getElementById('retourSkeleton');
        const content = document.getElementById('retourContent');

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

    function filterRetours(event = null) {
        if (event) {
            event.preventDefault();
        }

        const searchInput = document.getElementById('retourSearchInput');
        const rows = document.querySelectorAll('.retour-row');

        if (!searchInput) {
            return;
        }

        const searchValue = searchInput.value.toLowerCase().trim();

        rows.forEach(function (row) {
            const name = row.dataset.name || '';
            const ps = row.dataset.ps || '';

            const matchesSearch =
                searchValue === '' ||
                name.includes(searchValue) ||
                ps.includes(searchValue);

            row.style.display = matchesSearch ? '' : 'none';
        });
    }

    function openRetourFormFromButton(button) {
        const userId = button.dataset.userId;
        const psnummer = button.dataset.psnummer;
        const retourId = button.dataset.retourId;

        openRetourForm(userId, psnummer, retourId);
    }

    function openRetourForm(userId, psnummer, clickedRetourId) {
        const listSection = document.getElementById('retourListSection');
        const formSection = document.getElementById('retourFormSection');
        const psInput = document.getElementById('retourPsnummerInput');
        const checkboxList = document.getElementById('retourCheckboxList');

        if (!listSection || !formSection || !psInput || !checkboxList) {
            return;
        }

        listSection.style.display = 'none';
        formSection.style.display = 'block';

        psInput.value = psnummer || '-';
        checkboxList.innerHTML = '';

        const userRetours = retourItems.filter(function (item) {
            return String(item.user_id) === String(userId);
        });

        if (userRetours.length === 0) {
            checkboxList.innerHTML = `
                <div class="retour-checkbox-item">
                    <span>
                        <strong>Geen retouren gevonden</strong>
                        <small>Er zijn geen openstaande retouren gevonden voor deze student.</small>
                    </span>
                </div>
            `;

            return;
        }

        userRetours.forEach(function (item) {
            const checked = String(item.id) === String(clickedRetourId) ? 'checked' : '';

            checkboxList.insertAdjacentHTML('beforeend', `
                <label class="retour-checkbox-item">
                    <input type="checkbox" name="retour_ids[]" value="${item.id}" ${checked}>

                    <span>
                        <strong>${item.item_naam}</strong>
                        <small>${item.item_type} | Aantal: ${item.aantal} | Inleverdatum: ${item.retour_datum}</small>
                    </span>
                </label>
            `);
        });
    }

    function backToRetourList() {
        const formSection = document.getElementById('retourFormSection');
        const listSection = document.getElementById('retourListSection');

        if (!formSection || !listSection) {
            return;
        }

        formSection.style.display = 'none';
        listSection.style.display = 'block';
    }
</script>
@endsection