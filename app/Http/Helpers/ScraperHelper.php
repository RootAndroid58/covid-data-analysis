<?php

namespace App\Http\Helpers;

use voku\helper\HtmlDomParser;
use Illuminate\Support\Facades\Storage;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Category;
use App\Models\Resource;
use App\Models\SubCategory;
use \SpreadsheetReader;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ScraperHelper
{
    /**
     * ====================================
     * Scrapper States Here
     * ====================================
     *
     * http://covidhelpnagpur.in/
     */

    static function Scrap_IN_MH_Nagpur()
    {
        $data = array();
        $data["country"] = "IN";
        $data["country_key"] = "code";
        $data["state"] = "MH";
        $data["state_key"] = "state_code";
        $data["city"] = "Nagpur";
        $data["city_key"] = "name";
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
        $data['website'] = "http://covidhelpnagpur.in/";

        $dom = HtmlDomParser::file_get_html("http://covidhelpnagpur.in/");
        $element = $dom->find('#pool > tr')->innerhtml();

        $csvfile = '';
        foreach($element as $el){
            $csvfile .= trim(preg_replace('/\s+/',' ',strip_tags(str_replace(["&#13;","\t","\r","\n","<td>","</td>","<th>","</th>"],["","",'',",",'"','"','"','"'],$el))),",") . "\n";
        }
        try {
            Storage::disk('cron_temp')->put('INMHNagpur.csv', $csvfile);
            $update = new ScraperHelper;
            $data['status'] = $update->UpdateViaCSV('Resource',$data);

       } catch (\Exception $e) {

           throw $e;
       }

        return $data;
    }



    /**
     * ===========================================
     * Scrapper Helper Function Starts here
     * ===========================================
     */
    public static function UpdateViaCSV($model,$data)
    {
        try {
            $filename = $data['path'];
            $path     = storage_path('cron_temp\\' . $filename);

            $hasHeader = $data['hasHeader'];

            $fields = $data['fields'];
            $fields = array_flip(array_filter($fields));

            $modelName = $data['model'];
            $model     = 'App\\Models\\' . $modelName;

            $reader = new SpreadsheetReader($path);
            $insert = [];

            $success = array();


            foreach ($reader as $key => $row) {
                if ($hasHeader && $key == 0) {
                    continue;
                }

                $tmp = [];
                foreach ($fields as $header => $k) {

                    if (isset($row[$k])) {
                        $tmp[$header] = trim($row[$k],",");
                    }
                }


                if (count($tmp) > 0) {
                    $insert[] = $tmp;
                }
            }

            $for_insert = array_chunk($insert, 100);

            foreach ($for_insert as $insert_item) {

                $scraper = new ScraperHelper;
                $success[] = $scraper->updateorinsert($model,$insert_item,$data);
            }

            $rows  = count($insert);
            $table = Str::plural($modelName);
            $update = 0;
            $insert = 0;
            $sus = array();
            foreach($success as $item){
                $update = $update + $item['updates'];
                $insert = $insert + $item['inserts'];
                $sus[] = $item['success'];
            }

            File::delete($path);

            return array("success"=>$sus, 'rows' => $rows, 'table' => $table , "updates" => $update , 'new_data' => $insert);

        } catch (\Exception $ex) {

            throw $ex;
        }
    }

    public function getIDofALL($data)
    {

        $country = Country::where($data['country_key'],$data['country'])->first();
        $state = State::where($data['state_key'],$data['state'])->where('country_code',$country->code)->first();
        $city = City::where($data['city_key'],$data['city'])->where('state_code',$state->state_code)->where('country_code',$country->code)->first();

        return array('city' => $city, 'state' => $state, 'country' => $country);
    }

    public function getCategory($data)
    {
        $category = Category::where('name',$data)->first();
        if($category == null){
            $sub_category = SubCategory::with('category')->where('name',$data)->first();

            if($sub_category == null){
                $sub_category = SubCategory::create([
                    'name' => "$data",
                    'category_id' => 0,
                ]);
            }else{
                $category =$sub_category->category;
            }
        }else{
            $sub_category = null;
        }

        return array('category'=> $category , 'sub_category' => $sub_category);
    }

    public function updateorinsert($model,  $insert_item,$data)
    {
        $new_updates = 0;
        $new_data = 0;
        foreach($insert_item as $item){
            $categary = isset($item['categary']) ? $item['categary'] : null;
            $name = isset($item['name']) ? $item['name']: null;
            $phone_no = isset($item['phone_no']) ? $item['phone_no'] : null;
            $details = isset($item['details']) ?$item['details'] : null ;
            $url = isset($item['url']) ?$item['url'] : null ;
            $note = isset($item['note']) ?$item['note'] : null ;
            $address = isset($item['address']) ?$item['address'] : null ;
            $email = isset($item['email']) ?$item['email'] : null ;

            if($phone_no == null || $name == null || $categary == null) continue;
            if(filter_var($phone_no, FILTER_VALIDATE_URL)){
                continue;
            }
            $scraper = new ScraperHelper;
            $get_category_info = $scraper->getCategory($categary);
            $categary_id = $get_category_info['category'];
            $subcategory_id = $get_category_info['sub_category'];

            $location = $scraper->getIDofALL($data);

            if(!isset($location['city']) ){
                Log::debug("There is no city for ".$data['website']." #pool id ");
                continue;
            }
            if(!isset($location['state'])) {
                Log::debug("There is no state for ".$data['website']." #pool id ");
                continue;
            }
            if(!isset($location['country'])) {
                Log::debug("There is no country for ".$data['website']." #pool id ");
                continue;
            }
            $updatedata = $model::updateOrCreate(
                [
                    'name' => $name,
                    'country_id' => $location['country']->id,
                    'state_id' => $location['state']->id,
                    'city_id' => $location['city']->id,
                ],
                [
                    'phone_no' => $phone_no,
                    'details' => $details,
                    'url' => $url,
                    'note' => $note,
                    'address' => $address,
                    'email' => $email,
                ]
                );

            if($categary_id != null){
                $updatedata->categories()->sync($categary_id['id']);
            }else{
                $updatedata->categories()->sync(0);
            }
            if($subcategory_id != null){
                $updatedata->subcats()->sync($subcategory_id['id']);
            }
            if ($updatedata->wasRecentlyCreated) {
                $new_data ++;
            } else {
                if ($updatedata->wasChanged()) {
                    $new_updates ++;
                } else {
                    // model has NOT been assigned new values to one of its attributes and saved as is
                }

            }
        }
        // dd($updatedata);
        return array('updates'=> $new_updates,'inserts' => $new_data , 'success' => true);
    }
}
