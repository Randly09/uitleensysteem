<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('logboeks', function (Blueprint $table) {
            $table->text('opmerking')->nullable()->after('conditie');
        });
    }

    public function down(): void
    {
        Schema::table('logboeks', function (Blueprint $table) {
            $table->dropColumn('opmerking');
        });
    }
};