<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logboeks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');

            $table->string('item_type');
            $table->unsignedBigInteger('materiaal_id')->nullable();
            $table->unsignedBigInteger('materiaal_set_id')->nullable();

            $table->string('item_naam');
            $table->date('inleverdatum');
            $table->integer('hoeveelheid');
            $table->string('conditie')->nullable();
            $table->boolean('terug')->default(false);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('user')->cascadeOnDelete();
            $table->foreign('materiaal_id')->references('id')->on('materiaals')->nullOnDelete();
            $table->foreign('materiaal_set_id')->references('id')->on('materiaal_sets')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logboeks');
    }
};