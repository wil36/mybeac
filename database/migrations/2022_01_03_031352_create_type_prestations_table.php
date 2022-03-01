<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypePrestationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_prestations', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->double('montant')->default(0);
            $table->boolean('delete_ayant_droit')->default(0); //0 pour non et 1 pour oui
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('type_prestations');
    }
}