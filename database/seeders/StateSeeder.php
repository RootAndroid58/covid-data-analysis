<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo "loading file...";
        include(storage_path().'\dump\state_dump.php');
        echo "loading complete...";
        $save = array_chunk($states,500);
        foreach($save as $item){

            State::insert($item);
        }
        echo "done saving";
    }
}
