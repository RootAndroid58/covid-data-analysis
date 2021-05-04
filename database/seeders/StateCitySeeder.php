<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StateCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo "loading file...";
        include(storage_path().'\dump\cities_state_dump.php');
        echo "done loading file...";

        $save = array_chunk($country_state, 500);
        foreach ($save as $value) {
            \DB::table('city_state')->insert($value);
        }
    }
}
