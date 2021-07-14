<?php

namespace App\Http\Livewire\Frontend;

use App\Http\Helpers\ApiHelper;
use Livewire\Component;

class GlobalWorldMap extends Component
{
    public $data = array();
    public $date = 'today';
    public $type = 'active';
    public $var_name = 'worldometerMap_data';

    public function worldMap()
    {
        $this->data = ApiHelper::worldometer(null,null);

        $this->data = $this->data['meta'];
        for ($i=0; $i < count((array)$this->data); $i++) {
            if( !isset( $this->data[$i]['iso2'])){
                unset( $this->data[$i]);
            }
        }
        $this->data = array_values((array)$this->data);
        $this->emit('varset',$this->data, $this->var_name);
        unset($this->data);
    }

    public function date($value)
    {
        if($value == 'today' || $value == 'yesterday' || $value == 'yesterday2') $this->date = $value;
    }

    public function type($type)
    {
        $this->type = $type;
    }



    public function render()
    {
        $this->emit('WorldMap' , $this,$this->var_name);
        return view('livewire.frontend.global-world-map');
    }
}
