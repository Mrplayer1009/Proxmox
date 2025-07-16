<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id('id_reservation');
            $table->unsignedBigInteger('id_prestation');
            $table->unsignedBigInteger('id_client');
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->string('statut')->default('en_attente_paiement');
            $table->timestamps();

            $table->foreign('id_prestation')->references('id_prestation')->on('prestations')->onDelete('cascade');
            $table->foreign('id_client')->references('id_utilisateur')->on('utilisateurs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}; 