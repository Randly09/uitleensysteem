<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Kenza', 'Psnummer' => 'PS000001'],
            ['name' => 'Jeroen', 'Psnummer' => 'PS000002'],
            ['name' => 'Sophie', 'Psnummer' => 'PS000003'],
            ['name' => 'Daan', 'Psnummer' => 'PS000004'],
            ['name' => 'Emma', 'Psnummer' => 'PS000005'],
        ];

        foreach ($users as $user) {
            DB::table('user')->insert([
                'name' => $user['name'],
                'Psnummer' => $user['Psnummer'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $materialen = [
            ['naam' => 'Camera', 'hoeveelheid' => 5, 'beschikbaarheid' => 5, 'conditie' => 'Goed', 'foto_path' => 'images/materialen/camera.jpg'],
            ['naam' => 'Tripod', 'hoeveelheid' => 10, 'beschikbaarheid' => 10, 'conditie' => 'Goed', 'foto_path' => 'images/materialen/tripod.jpg'],
            ['naam' => 'Microfoon', 'hoeveelheid' => 10, 'beschikbaarheid' => 10, 'conditie' => 'Goed', 'foto_path' => 'images/materialen/microfoon.jpg'],
            ['naam' => 'Verlichting', 'hoeveelheid' => 6, 'beschikbaarheid' => 6, 'conditie' => 'Goed', 'foto_path' => null],
            ['naam' => 'Laptop', 'hoeveelheid' => 4, 'beschikbaarheid' => 4, 'conditie' => 'Redelijk', 'foto_path' => null],
        ];

        foreach ($materialen as $materiaal) {
            DB::table('materiaals')->insert([
                'naam' => $materiaal['naam'],
                'hoeveelheid' => $materiaal['hoeveelheid'],
                'beschikbaarheid' => $materiaal['beschikbaarheid'],
                'lokaal' => '2.41',
                'conditie' => $materiaal['conditie'],
                'opmerkingen' => null,
                'foto_path' => $materiaal['foto_path'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $sets = [
            ['naam' => 'Podcast set', 'hoeveelheid' => 3, 'omschrijving' => 'Camera, microfoon en tripod'],
            ['naam' => 'Interview set', 'hoeveelheid' => 2, 'omschrijving' => 'Camera en microfoon'],
            ['naam' => 'Fotografie set', 'hoeveelheid' => 4, 'omschrijving' => 'Camera, tripod en verlichting'],
        ];

        foreach ($sets as $set) {
            DB::table('materiaal_sets')->insert([
                'naam' => $set['naam'],
                'hoeveelheid' => $set['hoeveelheid'],
                'omschrijving' => $set['omschrijving'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $setMaterialen = [
            ['materiaal_set_id' => 1, 'materiaal_id' => 1, 'aantal' => 1],
            ['materiaal_set_id' => 1, 'materiaal_id' => 2, 'aantal' => 1],
            ['materiaal_set_id' => 1, 'materiaal_id' => 3, 'aantal' => 1],

            ['materiaal_set_id' => 2, 'materiaal_id' => 1, 'aantal' => 1],
            ['materiaal_set_id' => 2, 'materiaal_id' => 3, 'aantal' => 1],

            ['materiaal_set_id' => 3, 'materiaal_id' => 1, 'aantal' => 1],
            ['materiaal_set_id' => 3, 'materiaal_id' => 2, 'aantal' => 1],
            ['materiaal_set_id' => 3, 'materiaal_id' => 4, 'aantal' => 1],
        ];

        foreach ($setMaterialen as $item) {
            DB::table('materiaal_materiaal_set')->insert([
                'materiaal_id' => $item['materiaal_id'],
                'materiaal_set_id' => $item['materiaal_set_id'],
                'aantal' => $item['aantal'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $logboeken = [
            [1, 'materiaal', 1, null, 'Camera', '2026-06-12', 1, 'Goed', false],
            [1, 'materiaal', 3, null, 'Microfoon', '2026-06-12', 2, 'Goed', false],
            [2, 'materiaal', 2, null, 'Tripod', '2026-06-10', 1, 'Goed', true],
            [3, 'set', null, 1, 'Podcast set', '2026-06-15', 1, null, false],
            [4, 'materiaal', 1, null, 'Camera', '2026-06-20', 3, 'Goed', false],
            [5, 'set', null, 2, 'Interview set', '2026-06-14', 1, null, true],
            [2, 'materiaal', 4, null, 'Verlichting', '2026-06-22', 2, 'Goed', false],
            [3, 'materiaal', 5, null, 'Laptop', '2026-06-18', 1, 'Redelijk', false],
            [4, 'set', null, 3, 'Fotografie set', '2026-06-25', 2, null, false],
            [5, 'materiaal', 3, null, 'Microfoon', '2026-06-30', 1, 'Goed', true],

            [1, 'materiaal', 2, null, 'Tripod', '2026-07-01', 2, 'Goed', false],
            [2, 'set', null, 1, 'Podcast set', '2026-07-03', 1, null, true],
            [3, 'materiaal', 1, null, 'Camera', '2026-07-05', 1, 'Goed', true],
            [4, 'materiaal', 5, null, 'Laptop', '2026-07-07', 1, 'Redelijk', false],
            [5, 'set', null, 3, 'Fotografie set', '2026-07-09', 1, null, false],
        ];

        foreach ($logboeken as $logboek) {
            DB::table('logboeks')->insert([
                'user_id' => $logboek[0],
                'item_type' => $logboek[1],
                'materiaal_id' => $logboek[2],
                'materiaal_set_id' => $logboek[3],
                'item_naam' => $logboek[4],
                'inleverdatum' => $logboek[5],
                'hoeveelheid' => $logboek[6],
                'conditie' => $logboek[7],
                'terug' => $logboek[8],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}