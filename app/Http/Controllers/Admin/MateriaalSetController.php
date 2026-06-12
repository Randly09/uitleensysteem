<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MateriaalSet;
use Illuminate\Http\Request;

class MateriaalSetController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'set_naam' => 'required|string|max:255',
            'hoeveelheid' => 'required|integer|min:1',
            'lokaal' => 'nullable|string|max:255',
            'omschrijving' => 'nullable|string',
            'materialen' => 'required|array|min:1',
        ]);

        $selectedMaterialen = $this->getSelectedMaterialen($request);

        if ($selectedMaterialen->isEmpty()) {
            return back()
                ->withErrors(['materialen' => 'Selecteer minimaal één materiaal voor de set.'])
                ->withInput();
        }

        $set = MateriaalSet::create([
            'naam' => $data['set_naam'],
            'hoeveelheid' => $data['hoeveelheid'],
            'lokaal' => $data['lokaal'] ?? null,
            'omschrijving' => $data['omschrijving'] ?? null,
        ]);

        foreach ($selectedMaterialen as $materiaal) {
            $set->materialen()->attach($materiaal['id'], [
                'aantal' => $materiaal['aantal'],
            ]);
        }

        return redirect()->route('admin.materialen');
    }

    public function update(Request $request, MateriaalSet $set)
    {
        $data = $request->validate([
            'set_naam' => 'required|string|max:255',
            'hoeveelheid' => 'required|integer|min:1',
            'lokaal' => 'nullable|string|max:255',
            'omschrijving' => 'nullable|string',
            'materialen' => 'required|array|min:1',
        ]);

        $selectedMaterialen = $this->getSelectedMaterialen($request);

        if ($selectedMaterialen->isEmpty()) {
            return back()
                ->withErrors(['materialen' => 'Selecteer minimaal één materiaal voor de set.'])
                ->withInput();
        }

        $set->update([
            'naam' => $data['set_naam'],
            'hoeveelheid' => $data['hoeveelheid'],
            'lokaal' => $data['lokaal'] ?? null,
            'omschrijving' => $data['omschrijving'] ?? null,
        ]);

        $syncData = [];

        foreach ($selectedMaterialen as $materiaal) {
            $syncData[$materiaal['id']] = [
                'aantal' => $materiaal['aantal'],
            ];
        }

        $set->materialen()->sync($syncData);

        return redirect()->route('admin.materialen');
    }

    private function getSelectedMaterialen(Request $request)
    {
        return collect($request->input('materialen', []))
            ->filter(fn ($item) => isset($item['id']))
            ->map(function ($item) {
                return [
                    'id' => $item['id'],
                    'aantal' => max(1, (int) ($item['aantal'] ?? 1)),
                ];
            })
            ->values();
    }
}