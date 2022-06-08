<?php

namespace Database\Seeders;

use App\Models\Departement;
use Illuminate\Database\Seeder;

class DepartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Departement::factory(5)
            ->sequence(fn ($sequence) => ['code' => 'DPT' . $sequence->index])
            ->create();
    }
}
