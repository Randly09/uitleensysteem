@extends('user.layouts.app')

@section('content')
<div
    class="lenen-page"
    id="lenenPage"
    data-open-active="{{ session('success') || request()->has('psnummer') ? 'true' : 'false' }}"
>

    @if ($errors->any())
        <div class="lenen-error-popup">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if(session('success'))
        <div id="successPopup" class="lenen-popup-overlay">
            <div class="lenen-popup-card">
                <div class="success-icon">✓</div>
                <h2>Lening bevestigd</h2>
                <p>{{ session('success') }}</p>

                <button type="button" class="popup-main-btn" onclick="closeSuccessPopup()">
                    Verder
                </button>
            </div>
        </div>
    @endif

    <div id="lenen-menu" class="lenen-card menu-card">
        <button class="lenen-btn" type="button" onclick="showBorrowScreen()">
            Lenen
        </button>

        <button class="lenen-btn" type="button" onclick="showActiveLoansScreen()">
            Mijn leningen
        </button>
    </div>

    <form id="borrowForm" action="{{ route('user.lenen.store') }}" method="POST" class="hidden">
        @csrf

        <div id="borrowScreen" class="lenen-card form-card">
            <button type="button" class="small-back-btn" onclick="showMenu()">
                Terug
            </button>

            <label>PS-nummer</label>
            <input
                class="lenen-input"
                type="text"
                name="psnummer"
                placeholder="Bijv. PS000001"
                value="{{ old('psnummer', $psnummer ?? session('active_psnummer')) }}"
                required
            >

            <label>Zoeken</label>
            <div class="lenen-search-box">
                <input
                    id="borrowSearchInput"
                    type="text"
                    placeholder="Zoeken"
                    oninput="filterBorrowItems()"
                >
                <span>⌕</span>
            </div>

            <div class="items-box">
                @foreach($materialen as $materiaal)
                    <div class="borrow-item" data-search="{{ strtolower($materiaal->naam) }}">
                        <div>
                            <strong>{{ $materiaal->naam }}</strong>
                            <small>Materiaal | Beschikbaar: {{ $materiaal->beschikbaarheid }}</small>
                        </div>

                        <button
                            type="button"
                            class="add-item-btn"
                            data-item-id="materiaal:{{ $materiaal->id }}"
                            data-item-label="{{ $materiaal->naam }}"
                            data-item-type="Materiaal"
                            onclick="addBorrowItem(this)"
                            @if($materiaal->beschikbaarheid < 1) disabled @endif
                        >
                            +
                        </button>
                    </div>
                @endforeach

                @foreach($sets as $set)
                    <div class="borrow-item" data-search="{{ strtolower($set->naam) }}">
                        <div>
                            <strong>{{ $set->naam }}</strong>
                            <small>Set | Beschikbaar: {{ $set->hoeveelheid }}</small>
                        </div>

                        <button
                            type="button"
                            class="add-item-btn"
                            data-item-id="set:{{ $set->id }}"
                            data-item-label="{{ $set->naam }}"
                            data-item-type="Set"
                            onclick="addBorrowItem(this)"
                            @if($set->hoeveelheid < 1) disabled @endif
                        >
                            +
                        </button>
                    </div>
                @endforeach
            </div>

            <label>Geselecteerd</label>
            <div id="selectedItemsList" class="selected-items-list">
                <p class="empty-selected">Nog niets geselecteerd.</p>
            </div>

            <div id="hiddenItemsContainer"></div>

            <label>Datum & tijd terugbrengen</label>

            <div
                id="returnDateTimeBox"
                class="custom-datetime-box"
                data-old-return="{{ old('return_datetime') }}"
            >
                <input
                    id="returnDatePicker"
                    class="custom-date-input"
                    type="date"
                    onchange="updateReturnDateTimeHidden()"
                >

                <select
                    id="returnTimePicker"
                    class="custom-time-select"
                    onchange="updateReturnDateTimeHidden()"
                >
                    <option value="">Tijd</option>
                </select>

                <input
                    id="returnDateInput"
                    type="hidden"
                    name="return_datetime"
                    value="{{ old('return_datetime') }}"
                >
            </div>

            <button type="button" class="lenen-submit" onclick="openConfirmPopup()">
                Lenen
            </button>
        </div>
    </form>

    <div id="activeLoansScreen" class="lenen-card active-loans-card hidden">
        <button type="button" class="small-back-btn" onclick="showMenu()">
            Terug
        </button>

        <h2>Materialen geleend</h2>

        <form class="active-ps-form" method="GET" action="{{ route('user.lenen') }}">
            <input
                type="text"
                name="psnummer"
                placeholder="PS-nummer"
                value="{{ $psnummer ?? '' }}"
            >

            <button type="submit">Zoeken</button>
        </form>

        <div class="active-loans-list">
            @forelse($actieveLeningen as $lening)
                @php
                    if ($lening->item_type === 'materiaal') {
                        $naam = $lening->materiaal?->naam ?? $lening->item_naam;
                        $locatie = $lening->materiaal?->lokaal ?? '-';
                    } else {
                        $naam = $lening->set?->naam ?? $lening->item_naam;
                        $locatie = $lening->set?->lokaal ?? '-';
                    }
                @endphp

                <div class="active-loan-item">
                    <div>
                        <strong>{{ $naam }}</strong>
                        <small>{{ ucfirst($lening->item_type) }}</small>
                    </div>

                    <div>
                        <span>Terugbrengen op</span>
                        <strong>{{ \Carbon\Carbon::parse($lening->retour_datum)->format('d-m-Y H:i') }}</strong>
                    </div>

                    <div>
                        <span>Lokaal</span>
                        <strong>{{ $locatie }}</strong>
                    </div>
                </div>
            @empty
                <p class="no-active-loans">
                    Geen actieve leningen gevonden.
                </p>
            @endforelse
        </div>
    </div>

    <div id="confirmPopup" class="lenen-popup-overlay hidden">
        <div class="lenen-popup-card">
            <h2>Lening bevestigen</h2>

            <p>
                Weet je zeker dat je deze producten wilt lenen?
            </p>

            <div class="confirm-summary">
                <strong>Producten:</strong>
                <ul id="confirmItemsList"></ul>

                <strong>Terugbrengen op:</strong>
                <p id="confirmDateText"></p>
            </div>

            <div class="popup-actions">
                <button type="button" class="popup-secondary-btn" onclick="closeConfirmPopup()">
                    Nee
                </button>

                <button type="button" class="popup-main-btn" onclick="submitBorrowForm()">
                    Ja, lenen
                </button>
            </div>
        </div>
    </div>

</div>

<script>
    const selectedItems = new Map();

    document.addEventListener('DOMContentLoaded', function () {
        buildTimeOptions();
        restoreOldReturnDateTime();
        openCorrectStartScreen();
    });

    function openCorrectStartScreen() {
        const page = document.getElementById('lenenPage');

        if (!page) {
            return;
        }

        if (page.dataset.openActive === 'true') {
            showActiveLoansScreen();
        }
    }

    function buildTimeOptions() {
        const timePicker = document.getElementById('returnTimePicker');

        if (!timePicker) {
            return;
        }

        if (timePicker.options.length > 1) {
            return;
        }

        for (let hour = 0; hour < 24; hour++) {
            ['00', '30'].forEach(function (minutes) {
                const hourText = String(hour).padStart(2, '0');
                const timeValue = hourText + ':' + minutes;

                const option = document.createElement('option');
                option.value = timeValue;
                option.textContent = timeValue;

                timePicker.appendChild(option);
            });
        }
    }

    function restoreOldReturnDateTime() {
        const dateTimeBox = document.getElementById('returnDateTimeBox');
        const datePicker = document.getElementById('returnDatePicker');
        const timePicker = document.getElementById('returnTimePicker');

        if (!dateTimeBox || !datePicker || !timePicker) {
            return;
        }

        const oldValue = dateTimeBox.dataset.oldReturn;

        if (!oldValue) {
            return;
        }

        const normalizedValue = oldValue.replace(' ', 'T');
        const parts = normalizedValue.split('T');

        if (parts.length < 2) {
            return;
        }

        const datePart = parts[0];
        const timePart = parts[1].substring(0, 5);

        datePicker.value = datePart;
        timePicker.value = timePart;

        updateReturnDateTimeHidden();
    }

    function updateReturnDateTimeHidden() {
        const datePicker = document.getElementById('returnDatePicker');
        const timePicker = document.getElementById('returnTimePicker');
        const hiddenInput = document.getElementById('returnDateInput');

        if (!datePicker || !timePicker || !hiddenInput) {
            return;
        }

        if (datePicker.value && timePicker.value) {
            hiddenInput.value = datePicker.value + 'T' + timePicker.value;
        } else {
            hiddenInput.value = '';
        }
    }

    function hideAllScreens() {
        document.getElementById('lenen-menu').classList.add('hidden');
        document.getElementById('borrowForm').classList.add('hidden');
        document.getElementById('activeLoansScreen').classList.add('hidden');
    }

    function showMenu() {
        hideAllScreens();
        document.getElementById('lenen-menu').classList.remove('hidden');
    }

    function showBorrowScreen() {
        hideAllScreens();
        document.getElementById('borrowForm').classList.remove('hidden');
    }

    function showActiveLoansScreen() {
        hideAllScreens();
        document.getElementById('activeLoansScreen').classList.remove('hidden');
    }

    function filterBorrowItems() {
        const searchInput = document.getElementById('borrowSearchInput');

        if (!searchInput) {
            return;
        }

        const searchValue = searchInput.value.toLowerCase().trim();
        const items = document.querySelectorAll('.borrow-item');

        items.forEach(function (item) {
            const searchText = item.dataset.search || '';
            item.style.display = searchText.includes(searchValue) ? '' : 'none';
        });
    }

    function addBorrowItem(button) {
        const id = button.dataset.itemId;
        const label = button.dataset.itemLabel;
        const type = button.dataset.itemType;

        if (selectedItems.has(id)) {
            return;
        }

        selectedItems.set(id, {
            label: label,
            type: type,
        });

        button.disabled = true;

        renderSelectedItems();
    }

    function removeBorrowItem(id) {
        selectedItems.delete(id);

        const button = document.querySelector('[data-item-id="' + id + '"]');

        if (button) {
            button.disabled = false;
        }

        renderSelectedItems();
    }

    function renderSelectedItems() {
        const selectedItemsList = document.getElementById('selectedItemsList');
        const hiddenItemsContainer = document.getElementById('hiddenItemsContainer');

        if (!selectedItemsList || !hiddenItemsContainer) {
            return;
        }

        selectedItemsList.innerHTML = '';
        hiddenItemsContainer.innerHTML = '';

        if (selectedItems.size === 0) {
            selectedItemsList.innerHTML = '<p class="empty-selected">Nog niets geselecteerd.</p>';
            return;
        }

        selectedItems.forEach(function (item, id) {
            const selectedItem = document.createElement('div');
            selectedItem.className = 'selected-item';

            const textWrapper = document.createElement('span');

            const title = document.createElement('strong');
            title.textContent = item.label;

            const subtitle = document.createElement('small');
            subtitle.textContent = item.type;

            textWrapper.appendChild(title);
            textWrapper.appendChild(subtitle);

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.textContent = '×';
            removeButton.addEventListener('click', function () {
                removeBorrowItem(id);
            });

            selectedItem.appendChild(textWrapper);
            selectedItem.appendChild(removeButton);

            selectedItemsList.appendChild(selectedItem);

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'items[]';
            hiddenInput.value = id;

            hiddenItemsContainer.appendChild(hiddenInput);
        });
    }

    function openConfirmPopup() {
        updateReturnDateTimeHidden();

        const psInput = document.querySelector('[name="psnummer"]');
        const dateInput = document.getElementById('returnDateInput');

        if (!psInput.value.trim()) {
            alert('Vul je PS-nummer in.');
            return;
        }

        if (selectedItems.size === 0) {
            alert('Selecteer minimaal één materiaal of set.');
            return;
        }

        if (!dateInput.value) {
            alert('Kies een datum en tijd om terug te brengen.');
            return;
        }

        const selectedDate = new Date(dateInput.value);

        if (selectedDate <= new Date()) {
            alert('Kies een datum en tijd in de toekomst.');
            return;
        }

        const confirmItemsList = document.getElementById('confirmItemsList');
        const confirmDateText = document.getElementById('confirmDateText');

        confirmItemsList.innerHTML = '';

        selectedItems.forEach(function (item) {
            const listItem = document.createElement('li');
            listItem.textContent = item.label + ' (' + item.type + ')';
            confirmItemsList.appendChild(listItem);
        });

        confirmDateText.textContent = selectedDate.toLocaleString('nl-NL', {
            dateStyle: 'short',
            timeStyle: 'short',
        });

        document.getElementById('confirmPopup').classList.remove('hidden');
    }

    function closeConfirmPopup() {
        document.getElementById('confirmPopup').classList.add('hidden');
    }

    function submitBorrowForm() {
        updateReturnDateTimeHidden();
        document.getElementById('borrowForm').submit();
    }

    function closeSuccessPopup() {
        const popup = document.getElementById('successPopup');

        if (popup) {
            popup.classList.add('hidden');
        }

        showActiveLoansScreen();
    }
</script>
@endsection