<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            "kenza",
            "Jeroen",
            "Sophie",
            "Daan",
            "Emma"
        ];
        $psNummers = [
            "PS000001",
            "PS000002",
            "PS000003",
            "PS000004",
            "PS000005"
        ];
        $matname = [
            "Camera",
            "tripod",
            "microfoon",
        ];
        $basePath = "/images/materialen/";
        $matPath = [
            "camera.jpg",
            "tripod.jpg",
            "microfoon.jpg",
        ];
        $conditie = [
            "Nieuw",
            "Goed",
            "Redelijk",
            "Slecht",
        ];
        $SetItems = [
            "Camera",
            "tripod",
            "microfoon",
            "verlichting",
            "geluidssysteem",
            "laptop",
            "projector",
            "statief",
        ];
                for ($i = 0; $i < count($matname); $i++) {
        $int = rand(1, 10);    
        \DB::table("materiaals")->insert([
                "naam" => $matname[$i],
                "hoeveelheid" => $int,
                "lokaal" => "Lokaal " . rand(100, 130),
                "conditie" => "Conditie " . $conditie[rand(0, count($conditie) - 1)],
                "opmerkingen" => "Opmerking " . rand(1, 5),
                "foto_path" => $basePath . $matPath[$i],
                "beschikbaarheid" => $int-1,
                "created_at" => now(),
                "updated_at" => now(),
        ]);}
        for($i = 0; $i < count($SetItems); $i++) {
            \DB::table("materiaal_sets")->insert([
                "naam" => "Set " . ($i+1),
                "omschrijving" => "Deze set bevat: " . $SetItems[$i],
                "created_at" => now(),
                "updated_at" => now(),
            ]);
        }
        for($i = 0; $i < count($SetItems); $i++) {
            \DB::table("materiaal_materiaal_set")->insert([
                "materiaal_id" => $i+1,
                "materiaal_set_id" => $i+1,
                "aantal" => rand(1, 5),
                "created_at" => now(),
                "updated_at" => now(),
            ]);
        }
        for ($i = 0; $i < count($users); $i++) {
            \DB::table("user")->insert([
                "name" => $users[$i],
                "Psnummer" => $psNummers[$i],
                "created_at" => now(),
                "updated_at" => now(),
            ]);
        }

        for($i = 0; $i < 5; $i++) {
            \DB::table("retours")->insert([
                "materiaal_id" => $i+1,
                "aantal" => rand(1, 100),
                "retour_datum" => now()->addDays(rand(1, 30)),
                "is_returned" => rand(0, 1) == 1,
                "created_at" => now(),
                "updated_at" => now(),
            ]);
        for($i = 0; $i < 5; $i++) {
            \DB::table("retours_users")->insert([
                "retours_id" => $i+1,
                "users_id" => $i+1,
                "created_at" => now(),
                "updated_at" => now(),
            ]);
        }
    }
}
}