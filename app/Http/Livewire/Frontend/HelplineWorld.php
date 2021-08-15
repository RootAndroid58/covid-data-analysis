<?php

namespace App\Http\Livewire\Frontend;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Livewire\Component;

class HelplineWorld extends Component
{
    public $country = array();
    public $state = array();
    public $city = array();
    public $search = array();
    public $form = array(
        'country' => '',
        'state' => '',
        'city' => '',
        'search' => '',

    );
    public $rules = array(
        'form.country' => 'required',
        'form.state' => 'required',
        'form.city' => 'required',
        'form.search' => 'required|req',
    );

    public function country()
    {
        $this->country = Country::get();
    }

    public function state()
    {
        $this->state = State::where('country_code',$this->form['country'])->get();
        $this->form['city'] = '';
    }
    public function city()
    {
        if($this->form['state'] == ''){
            return $this->city = array();
        }
        $this->city = City::where('state_code',$this->form['state'])->get();
    }

    public function search()
    {
        $this->search = City::where('name','like',"%".$this->form['search']."%")->get();
        dd($this->search,$this->form['search']);
    }

    public function updated($field)
    {
        $this->validateOnly($field);
        if($field == 'form.country'){
            $this->state();
            $this->city();
            // = array();
            // $this->form['state'] = '';
            // $this->form['city'] = '';
        }
        if($field == 'form.state'){
            $this->city();
        }
        if($field == 'form.search'){
            $this->search();
        }
    }
    public function render()
    {
        return view('livewire.frontend.helpline-world');
    }
}
