<?php

namespace App\Http\Helpers;

use App\Http\Helpers\ApiHelper;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\VarDumper\Cloner\Data;

class CacheSorter
{
    static public function worldometer($today,$yesterday,$yesterday2)
    {
        $sort = new CacheSorter;
        $sorted_all = array();

        for ($i=0; $i < max(count($today),count($yesterday),count($yesterday2)); $i++) {
            if($today[$i]['country'] != ''){
                $yesterday_key = $sort->search($yesterday,$today[$i]['country'],'country');
                $yesterday2_key = $sort->search($yesterday2,$today[$i]['country'],'country');

                $sorted_all[] = $sort->worldometer_sort($today[$i],$yesterday[$yesterday_key],$yesterday2[$yesterday2_key]);
            }
        }
        Cache::tags(['prod','prod.worldometers'])->put('worldometer', $sorted_all, now()->addMinutes(30));

        return $sorted_all;
    }

    static public function worldometer_continent($data,$filter_data)
    {
        $sort = new CacheSorter;
        $continents_keys = array();
        $world_key = $sort->search($data,'World','country');
        $continents = array();
        $find = array(
            'North America',
            'Asia',
            'South America',
            'Europe',
            'Africa',
            'Oceania'
        );
        foreach($find as $val){
            $continents_keys[] = $sort->search($data,$val,'country');
        }

        foreach($continents_keys as $key){
            unset($filter_data[$key]);
        }
        for ($i=0; $i < count($continents_keys); $i++) {
            $continents[$i] = $data[$continents_keys[$i]];
        }
        unset($filter_data[$world_key]);
        $filter_data = array_values($filter_data);

        $response = array();
        foreach($continents as $continent){
            $response[] = $sort->worldometer_continent_sort($continent,$filter_data);
        }

        return $response;
    }
    static public function worldometer_countries($data,$filter_data)
    {
        $sort = new CacheSorter;
        $continents_keys = array();
        $world_key = $sort->search($data,'World','country');
        $continents = array();
        $find = array(
            'North America',
            'Asia',
            'South America',
            'Europe',
            'Africa',
            'Oceania'
        );
        foreach($find as $val){
            $continents_keys[] = $sort->search($data,$val,'country');
        }

        foreach($continents_keys as $key){
            unset($filter_data[$key]);
        }
        for ($i=0; $i < count($continents_keys); $i++) {
            $continents[$i] = $data[$continents_keys[$i]];
        }
        unset($filter_data[$world_key]);
        $filter_data = array_values($filter_data);

        return $filter_data;
    }

    static public function worldometer_states($today,$yesterday)
    {
        $sort = new CacheSorter;
        $sorted_all = array();

        for ($i=0; $i < max(count($today),count($yesterday)); $i++) {
            if($today[$i]['state'] != ''){
                $yesterday_key = $sort->search($yesterday,$today[$i]['state'],'state');

                $sorted_all[] = $sort->worldometer_state_sort($today[$i],$yesterday[$yesterday_key]);
            }
        }

        Cache::tags(['prod','prod.worldometers'])->put('worldometer.states', $sorted_all, now()->addMinutes(30));
        Cache::tags('temp.worldometers.states')->flush();

        return $sorted_all;
    }

    static public function historical($array,$array1,$array2 ,$search_key)
    {
        $sort = new CacheSorter;
        $data = array();
        for ($i=0; $i < max(count($array),count($array1),count($array2)); $i++) {

            $app = $array[$i];
            $search_key_app1 = $sort->search($array1,$app['Country/Region'],'Country/Region');
            $search_key_app2 = $sort->search($array2,$app['Country/Region'],'Country/Region');

            $data[] = $sort->hostorical_sort($array[$i],$array1[$search_key_app1],$array2[$search_key_app2],$search_key);
        }
        Cache::tags('prod','prod.historical')->put('historical_all',$data);
        Cache::tags('temp.historical')->flush();
        return $data;
    }

    public function worldometer_sort($today,$yesterday,$yesterday2)
    {
        $DataHelper = new DataHelper;
        $CacheSorter = new CacheSorter;
        $locations = $DataHelper->contries;
        $locations_key = $CacheSorter->search($locations,$today['country'],'country');
        if($locations_key == null){
            $location = array('iso2' => null , 'lat' => null , 'long' => null);
        }else{
            $location = $locations[$locations_key];
        }
        $sorted = array(
            'index'     => $today['index'] ? $today['index'] : null,
            'country'   => $today['country'],
            'continent' => $today['continent'] ? $today['continent'] : '',
            'iso2'      => $location['iso2'] ?  $location['iso2'] : null,
            'location'  =>  array(
                'latitude'  => $location['lat'] ? $location['lat']  : null ,
                'longitude' => $location['long'] ? $location['long']  : null,
            ),
            'timeline'  => array(
                'today' => array(
                    'cases'                 => $today['cases']              ? $today['cases'] : '',
                    'todayCases'            => $today['todayCases']         ? $today['todayCases'] : '',
                    'deaths'                => $today['deaths']             ? $today['deaths'] : '',
                    'todayDeaths'           => $today['todayDeaths']        ? $today['todayDeaths'] : '',
                    'recovered'             => $today['recovered']          ? $today['recovered'] : '',
                    'todayRecovered'        => $today['todayRecovered']     ? $today['todayRecovered'] : '',
                    'active'                => $today['active']             ? $today['active'] : '',
                    'critical'              => $today['critical']           ? $today['critical'] : '',
                    'casesPerOneMillion'    => $today['casesPerOneMillion'] ? $today['casesPerOneMillion'] : '',
                    'deathsPerOneMillion'   => $today['deathsPerOneMillion']? $today['deathsPerOneMillion'] : '',
                    'tests'                 => $today['tests']              ? $today['tests'] : '',
                    'testsPerOneMillion'    => $today['testsPerOneMillion'] ? $today['testsPerOneMillion'] : '',
                    'population'            => $today['population']         ? $today['population'] : '',
                    'oneCasePerPeople'      => $today['oneCasePerPeople']   ? $today['oneCasePerPeople'] : '',
                    'oneDeathPerPeople'     => $today['oneDeathPerPeople']  ? $today['oneDeathPerPeople'] : '',
                    'oneTestPerPeople'      => $today['oneTestPerPeople']   ? $today['oneTestPerPeople'] : '',

                ),
                'yesterday' => array(
                    'cases'                 => $yesterday['cases']              ? $yesterday['cases'] : '',
                    'todayCases'            => $yesterday['todayCases']         ? $yesterday['todayCases'] : '',
                    'deaths'                => $yesterday['deaths']             ? $yesterday['deaths'] : '',
                    'todayDeaths'           => $yesterday['todayDeaths']        ? $yesterday['todayDeaths'] : '',
                    'recovered'             => $yesterday['recovered']          ? $yesterday['recovered'] : '',
                    'todayRecovered'        => $yesterday['todayRecovered']     ? $yesterday['todayRecovered'] : '',
                    'active'                => $yesterday['active']             ? $yesterday['active'] : '',
                    'critical'              => $yesterday['critical']           ? $yesterday['critical'] : '',
                    'casesPerOneMillion'    => $yesterday['casesPerOneMillion'] ? $yesterday['casesPerOneMillion'] : '',
                    'deathsPerOneMillion'   => $yesterday['deathsPerOneMillion']? $yesterday['deathsPerOneMillion'] : '',
                    'tests'                 => $yesterday['tests']              ? $yesterday['tests'] : '',
                    'testsPerOneMillion'    => $yesterday['testsPerOneMillion'] ? $yesterday['testsPerOneMillion'] : '',
                    'population'            => $yesterday['population']         ? $yesterday['population'] : '',
                    'oneCasePerPeople'      => $yesterday['oneCasePerPeople']   ? $yesterday['oneCasePerPeople'] : '',
                    'oneDeathPerPeople'     => $yesterday['oneDeathPerPeople']  ? $yesterday['oneDeathPerPeople'] : '',
                    'oneTestPerPeople'      => $yesterday['oneTestPerPeople']   ? $yesterday['oneTestPerPeople'] : '',

                ),
                'yesterday2' => array(
                    'cases'                 => $yesterday2['cases']              ? $yesterday2['cases'] : '',
                    'todayCases'            => $yesterday2['todayCases']         ? $yesterday2['todayCases'] : '',
                    'deaths'                => $yesterday2['deaths']             ? $yesterday2['deaths'] : '',
                    'todayDeaths'           => $yesterday2['todayDeaths']        ? $yesterday2['todayDeaths'] : '',
                    'recovered'             => $yesterday2['recovered']          ? $yesterday2['recovered'] : '',
                    'todayRecovered'        => $yesterday2['todayRecovered']     ? $yesterday2['todayRecovered'] : '',
                    'active'                => $yesterday2['active']             ? $yesterday2['active'] : '',
                    'critical'              => $yesterday2['critical']           ? $yesterday2['critical'] : '',
                    'casesPerOneMillion'    => $yesterday2['casesPerOneMillion'] ? $yesterday2['casesPerOneMillion'] : '',
                    'deathsPerOneMillion'   => $yesterday2['deathsPerOneMillion']? $yesterday2['deathsPerOneMillion'] : '',
                    'tests'                 => $yesterday2['tests']              ? $yesterday2['tests'] : '',
                    'testsPerOneMillion'    => $yesterday2['testsPerOneMillion'] ? $yesterday2['testsPerOneMillion'] : '',
                    'population'            => $yesterday2['population']         ? $yesterday2['population'] : '',
                    'oneCasePerPeople'      => $yesterday2['oneCasePerPeople']   ? $yesterday2['oneCasePerPeople'] : '',
                    'oneDeathPerPeople'     => $yesterday2['oneDeathPerPeople']  ? $yesterday2['oneDeathPerPeople'] : '',
                    'oneTestPerPeople'      => $yesterday2['oneTestPerPeople']   ? $yesterday2['oneTestPerPeople'] : '',

                ),
            ),
        );
        return $sorted;

    }
    public function worldometer_state_sort($today,$yesterday)
    {
        $sorted = array(
            'index'     => $today['index'] ? $today['index'] : null,
            'state'   => $today['state'],
            'timeline'  => array(
                'today' => array(
                    'cases'                 => $today['cases']                  ? $today['cases'] : '',
                    'todayCases'            => $today['todayCases']             ? $today['todayCases'] : '',
                    'deaths'                => $today['deaths']                 ? $today['deaths'] : '',
                    'todayDeaths'           => $today['todayDeaths']            ? $today['todayDeaths'] : '',
                    'recovered'             => $today['recovered']              ? $today['recovered'] : '',
                    'active'                => $today['active']                 ? $today['active'] : '',
                    'casesPerOneMillion'    => $today['casesPerOneMillion']     ? $today['casesPerOneMillion'] : '',
                    'deathsPerOneMillion'   => $today['deathsPerOneMillion']    ? $today['deathsPerOneMillion'] : '',
                    'tests'                 => $today['tests']                  ? $today['tests'] : '',
                    'testsPerOneMillion'    => $today['testsPerOneMillion']     ? $today['testsPerOneMillion'] : '',
                    'population'            => $today['population']             ? $today['population'] : '',
                ),
                'yesterday' => array(
                    'cases'                 => $yesterday['cases']              ? $yesterday['cases'] : '',
                    'todayCases'            => $yesterday['todayCases']         ? $yesterday['todayCases'] : '',
                    'deaths'                => $yesterday['deaths']             ? $yesterday['deaths'] : '',
                    'todayDeaths'           => $yesterday['todayDeaths']        ? $yesterday['todayDeaths'] : '',
                    'recovered'             => $yesterday['recovered']          ? $yesterday['recovered'] : '',
                    'active'                => $yesterday['active']             ? $yesterday['active'] : '',
                    'casesPerOneMillion'    => $yesterday['casesPerOneMillion'] ? $yesterday['casesPerOneMillion'] : '',
                    'deathsPerOneMillion'   => $yesterday['deathsPerOneMillion']? $yesterday['deathsPerOneMillion'] : '',
                    'tests'                 => $yesterday['tests']              ? $yesterday['tests'] : '',
                    'testsPerOneMillion'    => $yesterday['testsPerOneMillion'] ? $yesterday['testsPerOneMillion'] : '',
                    'population'            => $yesterday['population']         ? $yesterday['population'] : '',
                ),
            ),
        );
        return $sorted;

    }

    public function hostorical_sort($array,$array1,$array2)
    {
        $remove = ['Province/State','Country/Region','Lat','Long'];
        $cases = array_diff_key($array,array_flip($remove));
        $deaths = array_diff_key($array1,array_flip($remove));
        $recovered = array_diff_key($array2,array_flip($remove));

        $data = array(
            'country'   => $array['Country/Region'] ? $array['Country/Region'] : '',
            'province'  => $array['Province/State'] ? $array['Province/State'] : '',
            'location'  =>  array(
                'latitude'  => $array['Lat'] ? $array['Lat']    : '',
                'longitude' => $array['Long']? $array['Long']   : '',
            ),

            'timeline'  => array(
                'cases'     => $cases       ? $cases      : null,
                'deaths'    => $deaths      ? $deaths     : null,
                'recovered' => $recovered   ? $recovered  : null
            ),
        );
        return $data;

    }

    public function worldometer_continent_sort($data,$filter_data)
    {
        $DataHelper = new DataHelper;
        $location = $DataHelper->continent;
        $sort = new CacheSorter;

        $dataset = $filter_data;

        $country_name = array();
        foreach($filter_data as $search){
            $country_key = $sort->search($filter_data,$data['continent'],'continent');
            $key[] = $country_key;
            unset($filter_data[$country_key]);
        }
        $key = array_filter($key, fn($value) => !is_null($value) && $value !== '' );
        $filter_data = array_values($filter_data);

        foreach($key as $search){
            $country_name[] = $dataset[$search]['country'];
        }

        $response = array(
            'continent' => $data['continent'],
            'continentInfo' => array(
                'latitude'  => $location[$data['continent']]['lat'] ? $location[$data['continent']]['lat']    : '',
                'longitude' => $location[$data['continent']]['long'] ? $location[$data['continent']]['long']    : '',
            ),
            'countries' => $country_name,
            'timeline' => array(
                'today' => array(
                    'cases'         => $data['timeline']['today']['cases'],
                    'todayCases'    => $data['timeline']['today']['todayCases'],
                    'deaths'        => $data['timeline']['today']['deaths'],
                    'todayDeaths'   => $data['timeline']['today']['todayDeaths'],
                    'recovered'     => $data['timeline']['today']['recovered'],
                    'todayRecovered'=> $data['timeline']['today']['todayRecovered'],
                    'active'        => $data['timeline']['today']['active'],
                    'critical'      => $data['timeline']['today']['critical'],
                ),
                'yesterday' => array(
                    'cases'         => $data['timeline']['yesterday']['cases'],
                    'todayCases'    => $data['timeline']['yesterday']['todayCases'],
                    'deaths'        => $data['timeline']['yesterday']['deaths'],
                    'todayDeaths'   => $data['timeline']['yesterday']['todayDeaths'],
                    'recovered'     => $data['timeline']['yesterday']['recovered'],
                    'todayRecovered'=> $data['timeline']['yesterday']['todayRecovered'],
                    'active'        => $data['timeline']['yesterday']['active'],
                    'critical'      => $data['timeline']['yesterday']['critical'],
                ),
                'yesterday2' => array(
                    'cases'         => $data['timeline']['yesterday2']['cases'],
                    'todayCases'    => $data['timeline']['yesterday2']['todayCases'],
                    'deaths'        => $data['timeline']['yesterday2']['deaths'],
                    'todayDeaths'   => $data['timeline']['yesterday2']['todayDeaths'],
                    'recovered'     => $data['timeline']['yesterday2']['recovered'],
                    'todayRecovered'=> $data['timeline']['yesterday2']['todayRecovered'],
                    'active'        => $data['timeline']['yesterday2']['active'],
                    'critical'      => $data['timeline']['yesterday2']['critical'],
                ),
            ),
        );
        return $response;
    }

    public function gov_sorter_Austria($data,$type = 'data')
    {

        $sort = new CacheSorter;
        $array = $sort->makeData($data,'Austria',$type);

        return $array;
    }
    public function gov_sorter_Austria_hospital($data)
    {
        $data = $data->CovidFallzahlen;
        $newData = array();
        foreach($data as $key => $temp){
            $temp_arr = array(
                'date' => isset($temp->Meldedat) ? $temp->Meldedat : '',
                'totalTests' => isset($temp->TestGesamt) ? $temp->TestGesamt : '',
                'date_' => isset($temp->MeldeDatum) ? $temp->MeldeDatum : '',
                'FZHosp' => isset($temp->FZHosp) ? $temp->FZHosp : '',
                'FZICU' => isset($temp->FZICU) ? $temp->FZICU : '',
                'FZHospFree' => isset($temp->FZHospFree) ? $temp->FZHospFree : '',
                'FZICUFree' => isset($temp->FZICUFree) ? $temp->FZICUFree : '',
                'StateID' => isset($temp->BundeslandID) ? $temp->BundeslandID : '',
                'state' => isset($temp->Bundesland) ? $temp->Bundesland : '',
            );
            $newData[$key] = $temp_arr;
        }
        // dd($newData,count($newData),count($data));
        $sort = new CacheSorter;
        $array = $sort->makeData($newData,'Austria','hospital');

        return $array;
    }



    static public function gov_sorter_canada($data)
    {
        $DataHelper = new DataHelper;
        $sort = new CacheSorter;
        $location = $DataHelper->contries;
        $info_key = $sort->search($location,'Canada','country');
        // array_multisort( array_column($data, "date"), SORT_ASC, $data );
        $data = collect($data)->groupBy('name')->all();


        $response = array(
            'country'  => $location[$info_key]['country'],
            'iso2'  => $location[$info_key]['iso2'],
            'location' => array(
                'lat' => $location[$info_key]['lat'],
                'long' => $location[$info_key]['long'],
            ),
            'gov' => $data,
        );
        return $response;
    }

    static public function gov_sorter_canada_timeline($data,$type)
    {
        $DataHelper = new DataHelper;
        $sort = new CacheSorter;
        $location = $DataHelper->contries;
        $info_key = $sort->search($location,'Canada','country');

        $response = array(
            'country'  => $location[$info_key]['country'],
            'iso2'  => $location[$info_key]['iso2'],
            'location' => array(
                'lat' => $location[$info_key]['lat'],
                'long' => $location[$info_key]['long'],
            ),
            $type => $data,
        );
        return $response;
    }

    static public function gov_sorter_Colombia($data)
    {
        $DataHelper = new DataHelper;
        $sort = new CacheSorter;
        $location = $DataHelper->contries;
        $info_key = $sort->search($location,'Colombia','country');

        $response = array(
            'country'  => $location[$info_key]['country'],
            'iso2'  => $location[$info_key]['iso2'],
            'location' => array(
                'lat' => $location[$info_key]['lat'],
                'long' => $location[$info_key]['long'],
            ),
            'data' => $data
        );
        return $response;
    }

    static public function gov_sorter_Colombia_bigdata($bigdata)
    {
        $DataHelper = new DataHelper;
        $sort = new CacheSorter;
        $location = $DataHelper->contries;
        $info_key = $sort->search($location,'Colombia','country');

        $ActiveCases = $recovered = $dead = $female = $male = $unknown = $caseID = 0;

        foreach($bigdata as $datas){
            foreach($datas as $data){
                if(isset($data['current_condition'])){
                    if($data['current_condition'] == 'Recuperado'){
                        $recovered ++;
                    }
                    if($data['current_condition'] == 'Fallecido' ||  $data['current_condition'] == 'fallecido'){
                        $dead ++;
                    }
                    if($data['current_condition'] == 'Activo'){
                        $ActiveCases ++;
                    }
                    if($data['current_condition'] == 'N/A'){
                        $unknown ++;
                    }
                }
                if(isset($data['sex'])){
                    if($data['sex'] == 'M'){
                        $male ++;
                    }
                    if($data['sex'] == 'F'){
                        $female ++;
                    }
                }
                if(isset($data['id']) && $data['id'] > $caseID){
                    $caseID = $data['id'];
                }

            }
        }
        $array = array(
            'country'  => $location[$info_key]['country'],
            'iso2'  => $location[$info_key]['iso2'],
            'location' => array(
                'lat' => $location[$info_key]['lat'],
                'long' => $location[$info_key]['long'],
            ),
            'active' => $ActiveCases,
            'recovered' => $recovered,
            'dead' => $dead,
            'female' => $female,
            'male' => $male,
            'unknown' => $unknown,
            'total_cases' => $caseID,
        );
        return $array;

    }

    static public function gov_sorter_germany($data)
    {
        $sort = new CacheSorter;
        $array = $sort->makeData($data,'Germany');

        return $array;
    }

    static public function gov_sorter_india($data)
    {
        $sort = new CacheSorter;
        $array = $sort->makeData($data,'India');

        return $array;
    }
    static public function gov_sorter_indo($data)
    {
        $sort = new CacheSorter;
        $array = $sort->makeData($data,'Indonesia');

        return $array;
    }
    static public function gov_sorter_israel($data)
    {
        $datas = ['lastUpdate','infectedPerDate','updatedPatientsOverallStatus','sickPerDateTwoDays','sickPerLocation',
        'patientsPerDate','deadPatientsPerDate','recoveredPerDay','testResultsPerDate','infectedPerDate_2','patientsPerDate_2',
        'doublingRate','infectedByAgeAndGenderPublic','isolatedDoctorsAndNurses','testResultsPerDate_2','contagionDataPerCityPublic',
        'hospitalStatus'];

        $DataHelper = new DataHelper;
        $sort = new CacheSorter;
        $location = $DataHelper->contries;
        $info_key = $sort->search($location,'Israel','country');
        $note = 'This error is form Israel government or Isreal data source not form my end!';

        $array = array(
            'country'  => $location[$info_key]['country'],
            'iso2'  => $location[$info_key]['iso2'],
            'location' => array(
                'lat' => $location[$info_key]['lat'],
                'long' => $location[$info_key]['long'],
            ),
            'data' => array(
                $datas[0] => isset($data[0]->data) ? $data[0]->data : ['error' => isset($data[0]->error) ? $data[0]->error : null , 'note' => $note],
                $datas[1] => isset($data[1]->data) ? $data[1]->data : ['error' => isset($data[1]->error) ? $data[1]->error : null , 'note' => $note],
                $datas[2] => isset($data[2]->data) ? $data[2]->data : ['error' => isset($data[2]->error) ? $data[2]->error : null , 'note' => $note],
                $datas[3] => isset($data[3]->data) ? $data[3]->data : ['error' => isset($data[3]->error) ? $data[3]->error : null , 'note' => $note],
                $datas[4] => isset($data[4]->data) ? $data[4]->data : ['error' => isset($data[4]->error) ? $data[4]->error : null , 'note' => $note],
                $datas[5] => isset($data[5]->data) ? $data[5]->data : ['error' => isset($data[5]->error) ? $data[5]->error : null , 'note' => $note],
                $datas[6] => isset($data[6]->data) ? $data[6]->data : ['error' => isset($data[6]->error) ? $data[6]->error : null , 'note' => $note],
                $datas[7] => isset($data[7]->data) ? $data[7]->data : ['error' => isset($data[7]->error) ? $data[7]->error : null , 'note' => $note],
                $datas[8] => isset($data[8]->data) ? $data[8]->data : ['error' => isset($data[8]->error) ? $data[8]->error : null , 'note' => $note],
                $datas[9] => isset($data[9]->data) ? $data[9]->data : ['error' => isset($data[9]->error) ? $data[9]->error : null , 'note' => $note],
                $datas[10] => isset($data[10]->data) ? $data[10]->data : ['error' => isset($data[10]->error) ? $data[10]->error : null , 'note' => $note],
                $datas[11] => isset($data[11]->data) ? $data[11]->data : ['error' => isset($data[11]->error) ? $data[11]->error : null , 'note' => $note],
                $datas[12] => isset($data[12]->data) ? $data[12]->data : ['error' => isset($data[12]->error) ? $data[12]->error : null , 'note' => $note],
                $datas[13] => isset($data[13]->data) ? $data[13]->data : ['error' => isset($data[13]->error) ? $data[13]->error : null , 'note' => $note],
                $datas[14] => isset($data[14]->data) ? $data[14]->data : ['error' => isset($data[14]->error) ? $data[14]->error : null , 'note' => $note],
                $datas[15] => isset($data[15]->data) ? $data[15]->data : ['error' => isset($data[15]->error) ? $data[15]->error : null , 'note' => $note],
                $datas[16] => isset($data[16]->data) ? $data[16]->data : ['error' => isset($data[16]->error) ? $data[16]->error : null , 'note' => $note],
            ),
        );

        return $array;
    }
    static public function gov_sorter_italy($data)
    {
        $sort = new CacheSorter;
        $array = $sort->makeData($data,'Italy');

        return $array;
    }
    static public function gov_sorter_NewZealand($data)
    {
        $sort = new CacheSorter;
        $array = $sort->makeData($data,'New Zealand');

        return $array;
    }
    static public function gov_sorter_nigeria($data)
    {
        $sort = new CacheSorter;
        $array = $sort->makeData($data,'Nigeria');

        return $array;
    }
    static public function gov_sorter_southafrica($data)
    {
        $DataHelper = new DataHelper;
        $sort = new CacheSorter;
        $location = $DataHelper->contries;
        $info_key = $sort->search($location,'South Africa','country');

        $array = array(
            'country'  => $location[$info_key]['country'],
            'iso2'  => $location[$info_key]['iso2'],
            'location' => array(
                'lat' => $location[$info_key]['lat'],
                'long' => $location[$info_key]['long'],
            ),
            'data' => $data,
        );
        // 'Eastern_Cape','Free_State','Gauteng','KwaZulu-Natal','Limpopo','Mpumalanga','North_West','Northern_Cape','Western_Cape'

        return $array;
    }

    static public function gov_sorter_southkorea($data)
    {
        $sort = new CacheSorter;
        $array = $sort->makeData($data,'S. Korea');

        return $array;
    }
    static public function gov_sorter_switzerland($data)
    {
        $sort = new CacheSorter;
        $complete_data = array();
        foreach($data as $temp){
            $complete_data = array_merge($complete_data,$temp);
        }

        $array = $sort->makeData($complete_data,'Swaziland');

        return $array;
    }
    static public function gov_sorter_uk($data)
    {
        $DataHelper = new DataHelper;
        $sort = new CacheSorter;
        $location = $DataHelper->contries;
        $info_key = $sort->search($location,'UK','country');
        $new_data = array();

        foreach($data as $temp){
            $new_data = array_merge($new_data, array(
                $temp->data
            ));
        }

        $array = array(
            'country'  => $location[$info_key]['country'],
            'iso2'  => $location[$info_key]['iso2'],
            'location' => array(
                'lat' => $location[$info_key]['lat'],
                'long' => $location[$info_key]['long'],
            ),
            'pages' => $new_data,
        );

        return $array;
    }
    static public function gov_sorter_vietnam($data)
    {
        $sort = new CacheSorter;
        $array = $sort->makeData($data,'Vietnam');

        return $array;
    }













    public function search($array,$find,$search_key)
    {
        if(isset($find)){
            foreach ($array as $key => $val) {
                if ($val[$search_key] === $find) {
                    return $key;
                }
            }
        }
        return null;
    }
    public function search_key($array,$find)
    {
        if(isset($find)){
            foreach ($array as $key => $val) {
                if ($val === $find || strcasecmp($val,$find) == 0) {
                    return $key;
                }
            }
        }
        return null;
    }
    public function search_key_by_find($array,$find,$search)
    {
        if(isset($find)){
            foreach ($array as $key => $val) {
                if ($val === $find[$search] || strcasecmp($val,$find[$search]) == 0) {
                    return $key;
                }
            }
        }
        return null;
    }

    public function makeData($data,$country,$name = 'data')
    {
        $DataHelper = new DataHelper;
        $sort = new CacheSorter;
        $location = $DataHelper->contries;
        $info_key = $sort->search($location,$country,'country');

        $array = array(
            'country'  => $location[$info_key]['country'],
            'iso2'  => $location[$info_key]['iso2'],
            'location' => array(
                'lat' => $location[$info_key]['lat'],
                'long' => $location[$info_key]['long'],
            ),
            $name => $data
        );

        return $array;
    }
}
