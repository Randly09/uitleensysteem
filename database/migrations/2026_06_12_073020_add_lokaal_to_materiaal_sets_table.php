<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materiaal_sets', function (Blueprint $table) {
            $table->string('lokaal')->nullable()->after('hoeveelheid');
        });
    }

    public function down(): void
    {
        Schema::table('materiaal_sets', function (Blueprint $table) {
            $table->dropColumn('lokaal');
        });
    }
};