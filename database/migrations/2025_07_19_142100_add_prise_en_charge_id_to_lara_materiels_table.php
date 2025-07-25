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
        Schema::table('lara_materiels', function (Blueprint $table) {
            $table->unsignedBigInteger('prise_en_charge_id')->nullable()->after('client_id');

            $table->foreign('prise_en_charge_id')
                ->references('id')
                ->on('lara_prises_en_charge')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lara_materiels', function (Blueprint $table) {
            //
        });
    }
};
