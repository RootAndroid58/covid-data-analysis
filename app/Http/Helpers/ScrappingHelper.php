<?php

namespace App\Http\Helpers;

use voku\helper\HtmlDomParser;
use Illuminate\Support\Facades\Storage;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Category;
use \SpreadsheetReader;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ScrappingHelper
{
    static function Scrap_IN_MH_Nagpur()
    {
        $data = array();
        $data["country"] = 103;
        $data["state"] = 1630;
        $data["city"] = 46502;
        $data['path'] = "/INMHNagpur.csv";
        $data['fields'] = array();
        $data['fields'][0] = 'categary';
        $data['fields'][1] = 'name';
        $data['fields'][2] = 'phone_no';
        $data['fields'][3] = 'details';
        $data['hasHeader'] = true;
        $data['model'] = 'Resource';
        $data['modelRelationship'] = array();
        $data['modelRelationship'][0] = "Category";



        $dom = HtmlDomParser::file_get_html("http://covidhelpnagpur.in/");
        $element = $dom->find('#pool > tr')->innerhtml();

        $csvfile = '';
        foreach($element as $el){
            $csvfile .= trim(preg_replace('/\s+/',' ',strip_tags(str_replace(["&#13;","\t","\r","\n","<td>","</td>","<th>","</th>"],["","",'',",",'"','"','"','"'],$el))),",") . "\n";
        }
        try {
            Storage::disk('cron_temp')->put('INMHNagpur.csv', $csvfile);
            $update = new ScrappingHelper;
            $update->UpdateViaCSV('Resource',$data);
            // $this->UpdateViaCSV('Resource',$data);
       } catch (\Exception $e) {
            dd($e);
       }

        return $data;
    }

    public static function UpdateViaCSV($model,$data)
    {
        dd($data);
        try {
            $filename = $data['path'];
            $path     = storage_path('cron_temp/' . $filename);

            $hasHeader = $data['hasHeader'];

            // $fields = $request->input('fields', false);
            $fields = $data['fields'];

            $modelName = $data['model'];
            $model     = 'App\\Models\\' . $modelName;

            $Relationship_Count = count($data['modelRelationship']);


            $reader = new SpreadsheetReader($path);
            $insert = [];

            foreach ($reader as $key => $row) {
                if ($hasHeader && $key == 0) {
                    continue;
                }

                $tmp = [];
                foreach ($fields as $header => $k) {
                    if (isset($row[$k])) {
                        $tmp[$header] = $row[$k];
                    }
                }

                if (count($tmp) > 0) {
                    $insert[] = $tmp;
                }
            }

            $for_insert = array_chunk($insert, 100);

            foreach ($for_insert as $insert_item) {
                $model::insert($insert_item);
            }

            $rows  = count($insert);
            $table = Str::plural($modelName);

            File::delete($path);

            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $rows, 'table' => $table]));

            return 0;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function getIDofALL($data)
    {
        $city = City::where('id',$data['city'])->orWhere('name',$data['city'])->first();
        $state = State::where('id',$data['state'])->orWhere('name',$data['state'])->first();
        $country = Country::where('id',$data['country'])->orWhere('name',$data['country'])->first();

        return array('city' => $city, 'state' => $state, 'country' => $country);
    }

    public function getCategory($data)
    {
        $category = Category::where('name',$data)->orWhere('id',$data)->first();

        if(!$category){

        }

        return $category;
    }


}
