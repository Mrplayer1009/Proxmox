<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('annonce_prestation', function (Blueprint $table) {
            $table->id('id_annonce_prestation');
            $table->unsignedBigInteger('id_prestataire');
            $table->unsignedBigInteger('id_utilisateur');
            $table->string('titre');
            $table->text('description')->nullable();
            $table->decimal('prix', 8, 2)->nullable();
            $table->string('statut')->default('en_cours');
            $table->foreign('id_prestataire')->references('id_prestataire')->on('prestataires')->onDelete('cascade');
            $table->timestamps();


        });
    }

    public function down()
    {
        Schema::dropIfExists('annonce_prestation');
    }
}; 