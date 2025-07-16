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
        Schema::create('lara_commentaires', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prise_en_charge_id');
            $table->text('contenu');
            $table->timestamps();

            // Ajoute la contrainte de clé étrangère ici
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
        Schema::dropIfExists('lara_commentaires');
    }
};
