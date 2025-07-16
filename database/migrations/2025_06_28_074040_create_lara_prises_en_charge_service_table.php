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
        Schema::create('lara_prises_en_charge_service', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prise_en_charge_id');
            $table->unsignedBigInteger('service_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lara_prises_en_charge_service');
    }
};
