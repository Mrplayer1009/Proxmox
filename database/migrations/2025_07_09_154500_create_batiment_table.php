<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('batiment', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->unsignedBigInteger('id_addresse');
            $table->timestamps();
            $table->foreign('id_addresse')->references('id')->on('addresse')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('batiment');
    }
}; 