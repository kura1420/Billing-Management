<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = User::factory(10)
            ->create();
        
        foreach ($users as $user) {
            $user->user_profiles()->create([
                'fullname' => $user->name,
                'email' => $user->email,
                'telp' => rand(1111, 9999),
                'handphone' => rand(1111, 9999),
                'departement_id' => \App\Models\Departement::inRandomOrder()->first()->id,
            ]);
        }
    }
}
