<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logboek;

class LogboekController extends Controller
{
    public function index()
    {
        $logboeken = Logboek::leftJoin('user', 'logboeks.user_id', '=', 'user.id')
            ->select('logboeks.*', 'user.Psnummer as psnummer')
            ->orderByDesc('logboeks.created_at')
            ->get();

        return view('admin.logboek', compact('logboeken'));
    }

    public function toggleTerug(Logboek $logboek)
    {
        $logboek->update([
            'terug' => !$logboek->terug,
        ]);

        return redirect()->route('admin.logboek');
    }
}