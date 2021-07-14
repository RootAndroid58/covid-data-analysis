<?php

namespace App\Http\Livewire\Frontend;

use App\Http\Helpers\ApiHelper;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use DataTables;
use Yajra\DataTables\Contracts\DataTable;

class WorldoMeterDataTable extends Component
{

    public $data = array();
    public $keys = [ 'temp.worldometers.today' ,'temp.worldometers.yesterday','temp.worldometers.yesterday2'];

    public function datatableInit()
    {
        foreach($this->keys as $key){
            $name = explode('.',$key);
            $data = Cache::get($key);
            for ($i=0; $i < count($data); $i++) {
                if($data[$i]['country'] == ''){
                    unset($data[$i]);
                }
            }
            $data = array_values($data);
            $this->data = array_merge($this->data,  [$name[2] => $data] );
            unset($data,$name);
        }


        //  dd(DataTables::collection($collection)->toJson());
        $this->emit('dataTable',$this->data);
        unset($this->data);

    }


    public function render()
    {
        return view('livewire.frontend.worldo-meter-data-table');
    }
}
