<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('livraison', function (Blueprint $table) {
            $table->id('id_livraison');
            $table->unsignedBigInteger('id_annonce');
            $table->unsignedBigInteger('id_livreur')->nullable();
            $table->unsignedBigInteger('id_utilisateur');
            $table->unsignedBigInteger('id_adresse_depart');
            $table->unsignedBigInteger('id_adresse_arrivee');
            $table->date('date_livraison')->nullable();
            $table->string('code_validation')->nullable();
            $table->float('poids')->nullable();
            $table->boolean('fragile')->default(false);
            $table->string('statut')->default('en_attente');
            $table->text('contenu')->nullable();
            $table->dateTime('date')->nullable();
            $table->string('modalite')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
            $table->foreign('id_annonce')->references('id_annonce')->on('annonce')->onDelete('cascade');
            $table->foreign('id_livreur')->references('id_livreur')->on('livreur')->onDelete('set null');
            $table->foreign('id_utilisateur')->references('id_utilisateur')->on('utilisateur')->onDelete('cascade');
            $table->foreign('id_adresse_depart')->references('id')->on('addresse')->onDelete('cascade');
            $table->foreign('id_adresse_arrivee')->references('id')->on('addresse')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('livraison');
    }
}; 