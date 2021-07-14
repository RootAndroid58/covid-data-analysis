<?php

namespace App\Http\Livewire\Frontend;

use App\Http\Helpers\ApiHelper;
use Livewire\Component;

class AppletrendsSearch extends Component
{
    public $search = array();

    public function search()
    {
        $this->search = ApiHelper::apple_mobility_country('prod.mobility.apple.country');
    }

    public function render()
    {
        return view('livewire.frontend.appletrends-search',['country' => $this->search ]);
    }
}
