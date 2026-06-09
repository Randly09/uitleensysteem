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
            'beschikbaarheid' => 'required|integer|min:0',
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

        Materiaal::create($data);

        return redirect()->route('admin.materialen');
    }
}