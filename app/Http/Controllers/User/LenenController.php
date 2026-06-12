<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Logboek;
use App\Models\Materiaal;
use App\Models\MateriaalSet;
use App\Models\Retour;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LenenController extends Controller
{
    public function create(Request $request)
    {
        $materialen = Materiaal::orderBy('naam')->get();
        $sets = MateriaalSet::orderBy('naam')->get();

        $psnummer = strtoupper(trim($request->query('psnummer', session('active_psnummer', ''))));

        $actieveLeningen = collect();

        if ($psnummer !== '') {
            $user = Users::where('Psnummer', $psnummer)->first();

            if ($user) {
                session(['active_psnummer' => $user->Psnummer]);

                $actieveLeningen = Retour::with(['users', 'materiaal', 'set'])
                    ->where('is_returned', false)
                    ->whereHas('users', function ($query) use ($user) {
                        $query->where('user.id', $user->id);
                    })
                    ->orderBy('retour_datum', 'asc')
                    ->get();
            }
        }

        return view('user.lenen', compact('materialen', 'sets', 'actieveLeningen', 'psnummer'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'psnummer' => 'required|string|max:255',
            'return_datetime' => 'required|date|after:now',
            'items' => 'required|array|min:1',
            'items.*' => 'required|string',
        ]);

        $psnummer = strtoupper(trim($data['psnummer']));
        $returnDateTime = Carbon::parse($data['return_datetime']);

        if (!in_array($returnDateTime->minute, [0, 30])) {
            throw ValidationException::withMessages([
                'return_datetime' => 'Kies een tijd met 00 of 30 minuten, bijvoorbeeld 10:00 of 10:30.',
            ]);
        }

        $user = Users::firstOrCreate(
            ['Psnummer' => $psnummer],
            ['name' => 'Student ' . $psnummer]
        );

        session(['active_psnummer' => $user->Psnummer]);

        DB::transaction(function () use ($data, $user, $returnDateTime) {
            foreach ($data['items'] as $selectedItem) {
                [$type, $id] = explode(':', $selectedItem);

                if ($type === 'materiaal') {
                    $materiaal = Materiaal::lockForUpdate()->findOrFail($id);

                    if ($materiaal->beschikbaarheid < 1) {
                        throw ValidationException::withMessages([
                            'items' => $materiaal->naam . ' is niet beschikbaar.',
                        ]);
                    }

                    $materiaal->decrement('beschikbaarheid');

                    Logboek::create([
                        'user_id' => $user->id,
                        'item_type' => 'materiaal',
                        'materiaal_id' => $materiaal->id,
                        'materiaal_set_id' => null,
                        'item_naam' => $materiaal->naam,
                        'inleverdatum' => $returnDateTime->toDateString(),
                        'hoeveelheid' => 1,
                        'conditie' => $materiaal->conditie,
                        'opmerking' => null,
                        'terug' => false,
                    ]);

                    $retour = Retour::create([
                        'item_type' => 'materiaal',
                        'materiaal_id' => $materiaal->id,
                        'materiaal_set_id' => null,
                        'item_naam' => $materiaal->naam,
                        'aantal' => 1,
                        'retour_datum' => $returnDateTime,
                        'is_returned' => false,
                    ]);

                    $retour->users()->attach($user->id);
                }

                if ($type === 'set') {
                    $set = MateriaalSet::lockForUpdate()->findOrFail($id);

                    if ($set->hoeveelheid < 1) {
                        throw ValidationException::withMessages([
                            'items' => $set->naam . ' is niet beschikbaar.',
                        ]);
                    }

                    $set->decrement('hoeveelheid');

                    Logboek::create([
                        'user_id' => $user->id,
                        'item_type' => 'set',
                        'materiaal_id' => null,
                        'materiaal_set_id' => $set->id,
                        'item_naam' => $set->naam,
                        'inleverdatum' => $returnDateTime->toDateString(),
                        'hoeveelheid' => 1,
                        'conditie' => null,
                        'opmerking' => null,
                        'terug' => false,
                    ]);

                    $retour = Retour::create([
                        'item_type' => 'set',
                        'materiaal_id' => null,
                        'materiaal_set_id' => $set->id,
                        'item_naam' => $set->naam,
                        'aantal' => 1,
                        'retour_datum' => $returnDateTime,
                        'is_returned' => false,
                    ]);

                    $retour->users()->attach($user->id);
                }
            }
        });

        return redirect()
            ->route('user.lenen', ['psnummer' => $user->Psnummer])
            ->with('success', 'Je lening is succesvol opgeslagen.');
    }
}