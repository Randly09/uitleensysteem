<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materiaal;
use Illuminate\Http\Request;

class MateriaalController extends Controller
{
    public function index()
    {
        $materialen = Materiaal::latest()->get();

        return view('admin.materialen', compact('materialen'));
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
            'beschikbaarheid' => 'required|integer|min:0',
            'lokaal' => 'nullable|string|max:255',
            'conditie' => 'nullable|string|max:255',
            'opmerkingen' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'split_aantal' => 'nullable|integer|min:1',
            'split_conditie' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('images/materialen'), $fileName);

            $data['foto_path'] = 'images/materialen/' . $fileName;
        }

        if (
            $request->filled('split_aantal') &&
            $request->filled('split_conditie') &&
            $request->split_aantal < $materiaal->hoeveelheid
        ) {
            $splitAantal = (int) $request->split_aantal;

            $materiaal->update([
                'naam' => $data['naam'],
                'hoeveelheid' => $data['hoeveelheid'] - $splitAantal,
                'beschikbaarheid' => max(0, $data['beschikbaarheid'] - $splitAantal),
                'lokaal' => $data['lokaal'] ?? null,
                'conditie' => $data['conditie'] ?? null,
                'opmerkingen' => $data['opmerkingen'] ?? null,
                'foto_path' => $data['foto_path'] ?? $materiaal->foto_path,
            ]);

            Materiaal::create([
                'naam' => $data['naam'],
                'hoeveelheid' => $splitAantal,
                'beschikbaarheid' => $splitAantal,
                'lokaal' => $data['lokaal'] ?? null,
                'conditie' => $data['split_conditie'],
                'opmerkingen' => $data['opmerkingen'] ?? null,
                'foto_path' => $data['foto_path'] ?? $materiaal->foto_path,
            ]);

            return redirect()->route('admin.materialen');
        }

        $materiaal->update($data);

        return redirect()->route('admin.materialen');
    }
}
