<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logboek;
use App\Models\Materiaal;
use App\Models\Retour;
use Illuminate\Http\Request;

class RetourController extends Controller
{
    public function index()
    {
        $retours = Retour::with(['users', 'materiaal', 'set'])
            ->where('is_returned', false)
            ->whereBetween('retour_datum', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->orderBy('retour_datum', 'asc')
            ->get();

        $retourItems = $retours->map(function ($retour) {
            $user = $retour->users->first();

            return [
                'id' => $retour->id,
                'user_id' => $user?->id,
                'psnummer' => $user?->Psnummer,
                'item_naam' => $retour->item_naam,
                'item_type' => $retour->item_type,
                'aantal' => $retour->aantal,
                'retour_datum' => $retour->retour_datum
                    ? $retour->retour_datum->format('d-m-Y H:i')
                    : '',
            ];
        })->values();

        return view('admin.retouren', compact('retours', 'retourItems'));
    }

    public function process(Request $request)
    {
        $data = $request->validate([
            'retour_ids' => 'required|array|min:1',
            'retour_ids.*' => 'exists:retours,id',
            'conditie' => 'required|string|max:255',
            'opmerking' => 'nullable|string',
        ]);

        $retours = Retour::with('users')
            ->whereIn('id', $data['retour_ids'])
            ->get();

        foreach ($retours as $retour) {
            $user = $retour->users->first();

            if (!$user) {
                continue;
            }

            $logboekQuery = Logboek::where('user_id', $user->id)
                ->where('item_type', $retour->item_type)
                ->where('terug', false);

            if ($retour->item_type === 'materiaal') {
                $logboekQuery->where('materiaal_id', $retour->materiaal_id);
            }

            if ($retour->item_type === 'set') {
                $logboekQuery->where('materiaal_set_id', $retour->materiaal_set_id);
            }

            $logboek = $logboekQuery->latest()->first();

            if ($logboek) {
                $logboek->update([
                    'terug' => true,
                    'conditie' => $retour->item_type === 'materiaal'
                        ? $data['conditie']
                        : $logboek->conditie,
                    'opmerking' => $data['opmerking'] ?? null,
                ]);
            }

            if ($retour->item_type === 'materiaal' && $retour->materiaal_id) {
                $materiaal = Materiaal::find($retour->materiaal_id);

                if ($materiaal) {
                    $materiaal->beschikbaarheid = min(
                        $materiaal->hoeveelheid,
                        $materiaal->beschikbaarheid + $retour->aantal
                    );

                    $materiaal->save();
                }
            }

            $retour->delete();
        }

        return redirect()->route('admin.retouren');
    }
}