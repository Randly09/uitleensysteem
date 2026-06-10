@extends('admin.layouts.app')

@section('content')

<div class="p-6 space-y-6">

    {{-- Skeleton --}}
    <div id="skeleton">
        <x-table-skeleton :rows="3" />
    </div>

    {{-- Table --}}
    <div id="table-content" class="hidden">

        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-semibold text-gray-800">
                Retouren
            </h1>

            <div class="text-sm text-gray-500">
                Overzicht van alle retouren
            </div>
        </div>

        <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">

            <table class="w-full text-sm text-left">

                <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Materiaal</th>
                        <th class="px-6 py-4">Gebruiker</th>
                        <th class="px-6 py-4">Datum retour</th>
                        <th class="px-6 py-4">hoeveelheid</th>
                        <th class="px-6 py-4 text-right">Actie</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @foreach($retours as $retour)
                        <tr class="hover:bg-gray-50 transition">

                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $retour->materiaal?->naam ?? '—' }}
                            </td>

                            <td class="px-6 py-4 text-gray-700">
                                {{ $retours->Psnummer ?? 'Geen gebruiker' }}
                            </td>

                            <td class="px-6 py-4 text-gray-500">
                                {{ $retour->created_at->format('d-m-Y') }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $retour->aantal ?? "Geen aantal" }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <button class="px-3 py-1 text-xs rounded-md bg-indigo-600 text-white hover:bg-indigo-700 transition">
                                    Retour
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
window.addEventListener('load', () => {
    setTimeout(() => {
        document.getElementById('skeleton').style.display = 'none';
        document.getElementById('table-content').classList.remove('hidden');
    }, 500);
});
</script>

@endsection