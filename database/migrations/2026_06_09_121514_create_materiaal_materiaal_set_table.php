<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('materiaal_materiaal_set', function (Blueprint $table) {
        $table->id();
        $table->integer('materiaal_id')->constrained();
        $table->integer('materiaal_set_id')->constrained();
        $table->integer('aantal')->default(1);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materiaal_materiaal_set');
    }
};
