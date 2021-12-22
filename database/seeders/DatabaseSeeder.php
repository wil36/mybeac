<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        Category::withoutEvents(function () {
            Category::create([
                'code' => 'C001',
                'libelle' => 'admin',
                'montant' => '5000',
            ]);
        });
        // \App\Models\User::factory(10)->create();
        User::withoutEvents(function () {
            // Create 1 admin
            User::create([
                'matricule' => 'A001',
                'nom' => 'admin',
                'prenom' => 'admin',
                'nationalité' => 'Camerounaise',
                'agence' => 'Bafoussam',
                'email' => 'admin@admin.com',
                'tel' => '+237 000 000 000',
                'date_naissance' => date("Y-m-d", strtotime(now())),
                'date_hadésion' => date("Y-m-d", strtotime(now())),
                'date_recrutement' => date("Y-m-d", strtotime(now())),
                'role' => 'admin',
                // 'categories_id' => 1,
                'theme' => 0,
                'status' => 1,
                'email_verified_at' => now(),
                'password' => Hash::make('1234'),
            ]);
            // // Create 2 redactors
            // User::factory()->count(252)->create([
            //     'role' => 'agent',
            // ]);
        });
    }
}