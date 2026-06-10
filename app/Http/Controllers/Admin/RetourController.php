<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Retour;
use App\Models\RetourUsers;

class RetourController extends Controller
{
public function index()
{
    $retourUser = RetourUsers::with(['retour', 'user'])->get();
    $retours2 = collect();

    foreach ($retourUser as $ru) {

        // skip broken relations safely
        if (!$ru->retour) {
            continue;
        }
        $ru->
        if ($ru->retour->is_returned === false) {
            $retours2->push([
                'retour' => $ru->retour,
                'psnummer' => $ru->
            ]);
        }
    }
    return view('admin.retouren', [
        'retours' => $retours2,
    ]);
}
}