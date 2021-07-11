<?php

namespace App\Http\Livewire\Frontend;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Statuspage extends Component
{

    public $data = array();


    public function init()
    {
        $this->data = Cache::get('cache.status');
        if(is_null($this->data)){
            Artisan::call('scraper:status');
            $this->data = Cache::get('cache.status');
        }
        // dd(Cache::get('temp.worldometers.today'));
    }

    public function render()
    {
        // dd($this->key);
        return view('livewire.frontend.statuspage',['data' => $this->data]);
    }
}
