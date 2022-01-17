<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // $this->call([
        //     CompaniesTableSeeder::class,
        //     ContactsTableSeeder::class
        // // ]);
        // Company::factory()->count(10)->create();
        // Contact::factory()->count(50)->create();

        // Company::factory()->hasContacts(rand(5,10))->count(10)->create();

        User::factory()->count(5)->create()->each(function ($user) {
            Company::factory()->has(
                Contact::factory()->count(5)->state(function ($attributes, Company $company) {
                    return ['user_id' => $company->user_id];
                })
            )->count(10)->create([
                'user_id' => $user->id
            ]);
        });
    }
}
