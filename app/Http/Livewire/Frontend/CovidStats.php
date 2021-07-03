<?php

namespace App\Http\Livewire\Frontend;

use App\Http\Helpers\ApiHelper;
use Livewire\Component;

class CovidStats extends Component
{
    public $active_cases = 0;
    public $deaths = 0;
    public $recovered = 0;

    public function loaded()
    {
        $raw = ApiHelper::worldometer(null,'World');
        $this->active_cases = $raw['meta']['timeline']['today']['cases'];
        $this->deaths = $raw['meta']['timeline']['today']['deaths'];
        $this->recovered = $raw['meta']['timeline']['today']['recovered'];

        $this->emit('counter' , $this);
    }

    public function render()
    {
        return view('livewire.frontend.covid-stats');
    }
}
