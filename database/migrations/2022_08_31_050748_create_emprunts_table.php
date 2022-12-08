<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpruntsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emprunts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('type');
            $table->text('objet');
            $table->text('link_lettre_souscription')->nullable();
            $table->decimal('montant', 18, 2);
            $table->decimal('montant_commission', 18, 2)->nullable();
            $table->dateTime('date');
            $table->dateTime('date_de_validation')->nullable();
            $table->dateTime('date_de_fin')->nullable();
            $table->string('etat');
            $table->foreignId('users_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::dropIfExists('emprunts');
    }
}