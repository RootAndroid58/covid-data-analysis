<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Country;
use App\Models\Resource;
use App\Models\State;
use Error;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use voku\helper\HtmlDomParser;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class TestController extends Controller
{
    public function index()
    {
        dd(datatables(Resource::all())->toJson());
    //     $dom = HtmlDomParser::file_get_html("http://covidhelpnagpur.in/");
    //     $element = $dom->find('#pool > tr');

    //     $header = '';
    //     $body = '';
    //     // dd(str_replace(['\n','\r','\t'],['','-',''],$element[4]->find('td')[2]->text()));
    //     foreach ($element[0]->find('th')->text() as $value) {
    //         $header .= '"' . $value . '",';
    //     }
    //     foreach($element as $node){
    //         foreach($node->find('td')->text() as $el){
    //             $body .= '"'. str_replace(array("\n", "\r", "\t"), [' ',',',''],$el) . '",';
    //         }
    //         $body .=  "\n";
    //     }
    //     $csvfile = $header  . $body;
    //     try {
    //         Storage::disk('cron_temp')->put('INMHNagpur.csv', $csvfile);

    //    } catch (\Exception $e) {

    //        throw $e;
    //    }




    }

    public function test2()
    {
        $dom = HtmlDomParser::file_get_html("https://www.worldometers.info/coronavirus/");
        $element = $dom->findMulti('#main_table_countries_today > tbody:nth-child(2) > tr');
        $element1 = $dom->find('#main_table_countries_today > thead > tr');

        $body = '';
        $count = 0;
        foreach($element as $node){
            foreach($node->find('td')->text() as $td){
                $body .= '"'.$td . '",';
            }
            $body .= "\n";
        }
        $header = "";
        foreach($element1->find('th')->text() as $th){
            $header .= '"'.str_replace(["\n",","],["","/"],$th).'",';
        }

        $data = $header ."\n". $body;
            try {
                Storage::disk('cron_temp')->put('worldometers.csv', $data);

           } catch (\Exception $e) {

               throw $e;
           }


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
