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
        'omschrijving' => 'nullable|string',
        'materialen' => 'required|array|min:1',
        'materialen.*.id' => 'required|exists:materiaals,id',
        'materialen.*.aantal' => 'required|integer|min:1',
    ]);

    $set = MateriaalSet::create([
        'naam' => $data['set_naam'],
        'hoeveelheid' => $data['hoeveelheid'],
        'omschrijving' => $data['omschrijving'] ?? null,
    ]);

    foreach ($data['materialen'] as $materiaal) {
        $set->materialen()->attach($materiaal['id'], [
            'aantal' => $materiaal['aantal'],
        ]);
    }

    return redirect()->route('admin.materialen');
}
}