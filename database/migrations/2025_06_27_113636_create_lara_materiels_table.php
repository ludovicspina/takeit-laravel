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
        Schema::create('lara_materiels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id'); // correspond à llx_societe.rowid
            $table->string('type');
            $table->string('marque')->nullable();
            $table->string('modele')->nullable();
            $table->string('numero_serie')->nullable();
            $table->text('accessoires')->nullable();
            $table->text('etat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lara_materiels');
    }
};
