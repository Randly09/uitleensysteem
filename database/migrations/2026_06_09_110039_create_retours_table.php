<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("user", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("Psnummer")->unique();
            $table->timestamps();
        });

        Schema::create('materiaals', function (Blueprint $table) {
            $table->id();
            $table->string('naam');
            $table->integer('hoeveelheid');
            $table->string('lokaal')->nullable();
            $table->string('conditie')->nullable();
            $table->text('opmerkingen')->nullable();
            $table->string('foto_path')->nullable();
            $table->integer('beschikbaarheid')->default(0);
            $table->timestamps();
        });

        Schema::create('retours', function (Blueprint $table) {
            $table->id();

            $table->string('item_type'); // materiaal or set
            $table->unsignedBigInteger('materiaal_id')->nullable();
            $table->unsignedBigInteger('materiaal_set_id')->nullable();

            $table->string('item_naam');
            $table->integer('aantal');
            $table->dateTime('retour_datum');
            $table->boolean('is_returned')->default(false);

            $table->timestamps();

            $table->foreign('materiaal_id')->references('id')->on('materiaals')->nullOnDelete();
        });

        Schema::create("retours_users", function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('retours_id');
            $table->unsignedBigInteger('users_id');

            $table->timestamps();

            $table->foreign('retours_id')->references('id')->on('retours')->cascadeOnDelete();
            $table->foreign('users_id')->references('id')->on('user')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retours_users');
        Schema::dropIfExists('retours');
        Schema::dropIfExists('materiaals');
        Schema::dropIfExists('user');
    }
};