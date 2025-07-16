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
        Schema::table('lara_prises_en_charge', function (Blueprint $table) {
            $table->string('statut')->default('en cours')->after('materiel_depose');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lara_prises_en_charge', function (Blueprint $table) {
            //
        });
    }
};
