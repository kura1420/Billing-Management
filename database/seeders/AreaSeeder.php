<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Area::factory(3)
            ->sequence(fn ($seq) => [
                'code' => 'AR' . $seq->index,
                'name' => 'Area ' . $seq->index,
            ])
            ->create();
    }
}
