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
        Schema::table('materiaals', function (Blueprint $table) {
            $table->integer('beschikbaarheid')->default(0)->after('hoeveelheid');
        });
    }

    public function down(): void
    {
        Schema::table('materiaals', function (Blueprint $table) {
            $table->dropColumn('beschikbaarheid');
        });
    }
};
