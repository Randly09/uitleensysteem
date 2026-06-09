<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materiaal;
use Illuminate\Http\Request;
use App\Models\MateriaalSet;

class MateriaalController extends Controller
{

public function index()
{
    $materialen = Materiaal::latest()->get();
    $sets = MateriaalSet::with('materialen')->latest()->get();

    return view('admin.materialen', compact('materialen', 'sets'));
}
public function store(Request $request)
{
    $data = $request->validate([
        'naam' => 'required|string|max:255',
        'hoeveelheid' => 'required|integer|min:0',
        'lokaal' => 'nullable|string|max:255',
        'conditie' => 'nullable|string|max:255',
        'opmerkingen' => 'nullable|string',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $data['beschikbaarheid'] = $data['hoeveelheid'];

    if ($request->hasFile('foto')) {
        $file = $request->file('foto');
        $fileName = time() . '_' . $file->getClientOriginalName();

        $file->move(public_path('images/materialen'), $fileName);

        $data['foto_path'] = 'images/materialen/' . $fileName;
    }

    Materiaal::create($data);

    return redirect()->route('admin.materialen');
}

public function update(Request $request, Materiaal $materiaal)
{
    $data = $request->validate([
        'naam' => 'required|string|max:255',
        'hoeveelheid' => 'required|integer|min:0',
        'lokaal' => 'nullable|string|max:255',
        'conditie' => 'nullable|string|max:255',
        'opmerkingen' => 'nullable|string',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    if ($request->hasFile('foto')) {
        $file = $request->file('foto');
        $fileName = time() . '_' . $file->getClientOriginalName();

        $file->move(public_path('images/materialen'), $fileName);

        $data['foto_path'] = 'images/materialen/' . $fileName;
    }

    if ($data['hoeveelheid'] < $materiaal->beschikbaarheid) {
        $data['beschikbaarheid'] = $data['hoeveelheid'];
    }

    $materiaal->update($data);

    return redirect()->route('admin.materialen');
}
}
