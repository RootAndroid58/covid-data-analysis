<?php

namespace App\Http\Livewire\Frontend;

use App\Http\Helpers\ApiHelper;
use Livewire\Component;


class WorldoMeterDataTable extends Component
{

    public $data = array();

    public function datatableInit()
    {
        $this->data = ApiHelper::worldometer(null,null);
        $this->data = $this->data['meta'];
        for ($i=0; $i < count((array)$this->data); $i++) {
            if( !isset( $this->data[$i]['iso2'])){
                unset( $this->data[$i]);
            }
        }
        $this->data = array_values((array)$this->data);
    }


    public function render()
    {
        return view('livewire.frontend.worldo-meter-data-table');
    }
}
