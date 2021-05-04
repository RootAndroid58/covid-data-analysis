<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CountryStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo "including file";
        include(storage_path().'\dump\cities_state_dump.php');
        echo "done now updating db";
        $save = array_chunk($country_state , 500);

        foreach($save as $item) {

            \DB::table('country_state')->insert($item);
        }

    }
}
