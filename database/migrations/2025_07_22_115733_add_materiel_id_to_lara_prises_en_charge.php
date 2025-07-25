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
            $table->unsignedBigInteger('materiel_id')->nullable()->after('client_id');
            $table->foreign('materiel_id')->references('id')->on('lara_materiels')->onDelete('set null');

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
