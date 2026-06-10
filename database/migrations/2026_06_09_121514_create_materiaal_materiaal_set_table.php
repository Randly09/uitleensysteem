<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materiaal_materiaal_set', function (Blueprint $table) {
            $table->id();

            $table->foreignId('materiaal_id')
                ->constrained('materiaals')
                ->cascadeOnDelete();

            $table->foreignId('materiaal_set_id')
                ->constrained('materiaal_sets')
                ->cascadeOnDelete();

            $table->integer('aantal')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materiaal_materiaal_set');
    }
};