<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('nationalité');
            $table->string('agence');
            $table->string('matricule');
            $table->string('email');
            $table->string('tel');
            $table->string('sexe')->default('Masculin');
            $table->dateTime('date_naissance')->default(Carbon::now());
            $table->dateTime('date_hadésion')->default(Carbon::now());
            $table->dateTime('date_recrutement')->default(Carbon::now());
            $table->string('role')->default('agent');
            $table->boolean('theme')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->foreignId('categories_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
}