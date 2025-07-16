<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('annonce', function (Blueprint $table) {
            $table->id('id_annonce');
            $table->unsignedBigInteger('id_utilisateur');
            $table->string('titre');
            $table->unsignedBigInteger('id_addresse');
            $table->integer('nombre')->default(1);
            $table->decimal('poids', 8, 2)->default(0);
            $table->boolean('fragile')->default(0);
            $table->text('description');
            $table->decimal('prix', 8, 2);
            $table->string('statut')->default('en_cours');
            $table->date('date_limite')->nullable();
            $table->string('type_colis');
            $table->timestamps();

            $table->foreign('id_utilisateur')->references('id_utilisateur')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('id_addresse')->references('id')->on('addresse')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('annonce');
    }
}; 