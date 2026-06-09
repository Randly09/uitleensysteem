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
        Schema::create('retours', function (Blueprint $table) {
            $table->id();
            $table->integer('materiaals_id');
            $table->integer('users_id');
            $table->date('retour_datum');
            $table->timestamps();
        });
        Schema::create("retours_users", function (Blueprint $table) {
            $table->id();
            $table->integer('retours_id');
            $table->integer('users_id');
            $table->timestamps();
        });
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materiaals');
        Schema::dropIfExists('user');
        Schema::dropIfExists('retours_users');
        Schema::dropIfExists('retours');
    }
};
