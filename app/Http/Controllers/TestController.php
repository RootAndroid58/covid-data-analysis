<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Error;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

class TestController extends Controller
{
    public function index()
    {
        // $token = "ya29.a0AfH6SMDxocPmQa8coi7oLwhmm1OALTdnIXSIr0nSLO-0wgxpuMnQFb4Sz-WOXZuOImUwZXap8vMgqWDB78lAOFSA6W53Bmp_6Su9bkLCvAlE4USj9aDj_f099XT5IAm8KGsSLmoYJU9EGRLdOqUZduSEWAv_";
        // $user = Socialite::driver('google')->userFromToken($token);
        // dd($user);

        $data = Country::with('states.cities')->where('name','India')->get();
        dd($data[0]->states[21]->cities[0]);
        // echo "starting";
        // dd(storage_path());
        // // // $nrwe = \DB::table(\DB::raw('countries, states'))->select('*')->where('countries.slug','=','IN')->get();
        // // // $raw = \DB::statement('select * from `countries` , `states` where countries.slug = "IN" AND `countries`.`slug` = `states`.`slug`');
        // // // $dont = \App\Models\City::latest()->paginate(10);
        // // $data = \App\Models\Country::get()->pluck('slug','id');
        // // $data1 = \App\Models\State::get()->pluck('slug','id');
        // // // print_r('done');
        // // dd($data,$data1);
        // // $country = Country::select('id','code')->get();
        // $state = State::select('id','country_code','state_code')->get();
        // $city = city::select('id','state_code','country_code')->get();

        // $count = count($city) + 1 ;
        // $i = 1;
        // // dd($count);
        // foreach($city as $value){
        //     // dd($value['country_code']);
        //     if($i > $count){
        //         break;
        //     }
        //     $_country = Country::where('code',$value['country_code'])->first();
        //     $_state = State::where('state_code',$value['state_code'])->where('country_code',$_country->code)->first();
        //     // $_city = city::where('id',$i)->first();
        //     // dd($_city,$_country,$_state);
        //     if(isset($_state)  &&  $_state->id ){
        //         $data1 = array(
        //             'state_id' => $_state->id,
        //             'city_id' => $value['id'],
        //         );
        //     }
        //     $tochange[] = $data1;
        //     echo $i;
        //     $i++;
        // }
        // // for ($i=1; $i <= $count; $i++) {
        // //     $_country = Country::where('code',$city[$i]->country_code)->first();
        // //     $_state = State::where('state_code',$city[$i]->state_code)->where('country_code',$_country->code)->first();
        // //     $_city = city::where('id',$i)->first();
        // //     // dd($_city,$_country,$_state);
        // //     if(isset($_state) && isset($_city) &&  $_state->id &&  $_city->id){
        // //         $data1 = array(
        // //             'state_id' => $_state->id,
        // //             'city_id' => $_city->id,
        // //         );
        // //     }
        // //     $tochange[] = $data1;


        // //     // \DB::table('city_state')->insert($data1);
        // // }
        // print_r('saving to db');
        // $data = array_chunk($tochange,500);

        // foreach($data as $datas){
        //     // dd($datas);
        //     // \DB::table('city_state')->insert($datas);
        // }
        // // dd($data);
        // dd('done');


        // // foreach ($state as $item) {
        // // dd($item);
        // //     $data = array(
        // //         'country_id' => $_country[0]->id,
        // //         'state_id' => $_state[0]->id,
        // //     );
        // //     \DB::table('country_state')->insert($data);
        // // }

        // dd($country[0] ,$state[0],$city[0]);


    }

    public function test2()
    {
        $state = State::select('id','country_code','state_code')->get();
        $city = city::select('id','state_code','country_code')->get();
dd(count($city));
        foreach ($state as $item) {
            // dd($item);
            $_country = Country::where('code',$item['country_code'])->first();
            $data = array(
                'country_id' => $_country->id,
                'state_id' => $item['id'],
            );
            // \DB::table('country_state')->insert($data);
        }
        dd('done');
    }

    public function testMail(Request $request)
    {
        try{
            $to_name = 'ETHYT';
            $to_email = 'rootand58@gmail.com';
            $data = array('name'=>"Sam Jose", "body" => "Test mail");

            Mail::send('emails.mail', $data, function($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)
                        ->subject('Artisans Web Testing Mail');
                // $message->from('contact@crada.ga','Artisans Web');
            });
        }catch(Error $e){
            dd($e);
        }

    }
    public function throwerror(Request $request)
    {
        // throw new Error('test');
        $state= new State;
        $state->getAttributes();
        dd($state->getFillable(),$state);
    }
}
