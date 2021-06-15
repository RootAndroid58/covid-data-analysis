<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class GovernmentApiController extends Controller
{
    public $gov = array(0 => 'Austria',1 => 'Canada', 2 => 'Colombia',3 => 'Germany', 4 => 'India', 5 => 'Indonesia',
    6 => 'Israel', 7 => 'Italy', 8 => 'NewZealand', 9 => 'Nigeria', 10 => 'SouthAfrica', 11 => 'SouthKorea', 12 => 'Switzerland',
    13 => 'UK', 14 => 'Vietnam');

    public function get_Gov(Request $request)
    {
        $gov = $this->gov;
        $response = array(
            'supported' => $gov
        );
        return ApiHelper::SuccessorFail(200,$response,true);

    }

    public function gov(Request $request,$country)
    {
        $gov = $this->gov;
        switch ($country) {
            case $gov[0]:
                $data = $this->get_Austria($request);
                break;
            case $gov[1]:
                $data = $this->get_Canada($request);
                break;

            case $gov[2]:
                $data = $this->get_Colombia($request);
                break;

            case $gov[3]:
                $data = $this->get_Germany($request);
                break;
            case $gov[4]:
                $data = $this->get_India($request);
                break;
            case $gov[5]:
                $data = $this->get_Indonesia($request);
                break;
            case $gov[6]:
                $data = $this->get_Israel($request);
                break;
            case $gov[7]:
                $data = $this->get_Italy($request);
                break;
            case $gov[8]:
                $data = $this->get_NewZealand($request);
                break;
            case $gov[9]:
                $data = $this->get_Nigeria($request);
                break;
            case $gov[10]:
                $data = $this->get_SouthAfrica($request);
                break;
            case $gov[11]:
                $data = $this->get_SouthKorea($request);
                break;
            case $gov[12]:
                $data = $this->get_Switzerland($request);
                break;
            case $gov[13]:
                $data = $this->get_UK($request);
                break;
            case $gov[14]:
                $data = $this->get_Vietnam($request);
                break;

            default:
                $data = ApiHelper::SuccessorFail(400,['error' => 'Supported Countries '. implode(',',$gov)]);
                break;
        }
        return response()->json($data);
    }

        /**
         * Austria Gov data
         * ===========done==========
         */

        public function get_Austria(Request $request)
        {
            $data = ['historical','byAge','byDistrict','hospital','version'];
            $request->validate([
                'type'  => 'sometimes|in:'.implode(',',$data).'|nullable',
            ]);

            $type = $request->input('type');

            switch ($type) {
                case $data[0]:
                    $cacheKey = 'prod.gov.austria.historical';
                    break;
                case $data[1]:
                    $cacheKey = 'prod.gov.austria.byage';
                    break;
                case $data[2]:
                    $cacheKey = 'prod.gov.austria.bydistrict';
                    break;
                case $data[3]:
                    $cacheKey = 'prod.gov.austria.hospital';
                    break;
                case $data[4]:
                    $cacheKey = 'prod.gov.austria.version';
                    break;

                default:
                    $cacheKey = false;
                    break;
            }
            if($cacheKey){
                $response = ApiHelper::gov_southafrica($cacheKey);
            }else{
                $response = ApiHelper::SuccessorFail(400,array('message' => "missing parameter 'type' or invalid values supported values: ".implode(',',$data)));
            }

            return $response;

        }

        /**
         * Canada https://health-infobase.canada.ca/src/data/covidLive/covid19.csv
         * ===========done==========
         */
        public function get_Canada(Request $request)
        {
            $data = ['default','active','cases','deaths','recovered','testing','vaccine_administration','vaccine_completion','vaccine_distribution'];

            $type = $request->input('type');
            switch ($type) {
                case $data[0]:
                    $cacheKey = 'prod.gov.canada';
                    break;
                case $data[1]:
                    $cacheKey = 'prod.gov.canada.timeline_active';
                    break;
                case $data[2]:
                    $cacheKey = 'prod.gov.canada.timeline_cases';
                    break;
                case $data[3]:
                    $cacheKey = 'prod.gov.canada.timeline_deaths';
                    break;
                case $data[4]:
                    $cacheKey = 'prod.gov.canada.timeline_recovered';
                    break;
                case $data[5]:
                    $cacheKey = 'prod.gov.canada.timeline_testing';
                    break;
                case $data[6]:
                    $cacheKey = 'prod.gov.canada.timeline_vaccine_administration';
                    break;
                case $data[7]:
                    $cacheKey = 'prod.gov.canada.vaccine_completion';
                    break;
                case $data[8]:
                    $cacheKey = 'prod.gov.canada.vaccine_distribution';
                    break;

                default:
                    $cacheKey = false;
                    break;
            }

            if($cacheKey){
                $response = ApiHelper::gov_Canada($cacheKey);
            }else{
                $response = ApiHelper::SuccessorFail(400,array('message' => "missing parameter 'type' or invalid values supported values: ".implode(',',$data)));
            }

            return $response;

        }

         /**
          * Colombia https://www.datos.gov.co/api/views/gt2j-8ykr/rows.json
          * ===========done==========
          */
        public function get_Colombia(Request $request)
        {
            $data = ['vaccines_allocations','pcr_tests'];

            $type = $request->input('type');

            switch ($type) {
                // case $data[0]:
                //     $cacheKey = 'prod.gov.colombia.bigdata';
                //     break;
                case $data[0]:
                    $cacheKey = 'prod.gov.canada.vaccines_allocations';
                    break;
                case $data[1]:
                    $cacheKey = 'prod.gov.canada.pcr_tests_municipal';
                    break;

                default:
                $cacheKey = false;
                    break;
            }

            if($cacheKey){
                $response = ApiHelper::gov_colombia($cacheKey);
            }else{
                $response = ApiHelper::SuccessorFail(400,array('message' => "missing parameter 'type' or invalid values supported values: ".implode(',',$data)));
            }

            return $response;
        }

        /**
         *  Germany https://www.rki.de/DE/Content/InfAZ/N/Neuartiges_Coronavirus/Fallzahlen.html
         * ===========done==========
         */
        public function get_Germany(Request $request)
        {
            $cacheKey = 'prod.gov.germany';

            $response = ApiHelper::gov_germany($cacheKey);

            return $response;
        }
        // $update = new ScraperHelper;
        // $resp = $update->curlUrl("https://www.rki.de/DE/Content/InfAZ/N/Neuartiges_Coronavirus/Fallzahlen.html");
        // $dom = HtmlDomParser::str_get_html($resp);

        // $table = $dom->find('table > tbody');
        // $data = '';
        // $fields = array(
        //     'no','newCases','Cases in the last 7 days','7-day incidence','deaths'
        // );
        // foreach($table->find('tr') as $tr){
        //     foreach($tr->find('td')->text() as $td){
        //         $data .= '"'.$td . '",';
        //     }
        //     $data .= "\n";
        // }

        /**
         * India https://www.mohfw.gov.in/data/datanew.json
         * ===========done==========
         */
        public function get_India(Request $request)
        {
            $cacheKey = 'prod.gov.india';

            $response = ApiHelper::gov_india($cacheKey);

            return $response;
        }

        /**
         * Indonesia
         *
         * data https://data.covid19.go.id/public/api/data.json
         * update https://data.covid19.go.id/public/api/update.json
         * prev https://data.covid19.go.id/public/api/prov.json
         *
         * ==== skipped=====
         */

        public function get_Indonesia(Request $request)
        {
            $data = ['data','update','prev'];

            $type = $request->input('type');

            switch ($type) {
                case $data[0]:
                    $cacheKey = 'prod.gov.indonesia.data';
                    break;
                case $data[1]:
                    $cacheKey = 'prod.gov.indonesia.update';
                    break;
                case $data[2]:
                    $cacheKey = 'prod.gov.indonesia.prev';
                    break;

                default:
                $cacheKey = false;
                    break;
            }

            if($cacheKey){
                $response = ApiHelper::gov_Indonesia($cacheKey);
            }else{
                $response = ApiHelper::SuccessorFail(400,array('message' => "missing parameter 'type' or invalid values supported values: ".implode(',',$data)));
            }



            return $response;
        }

        /**
         * Israel https://datadashboardapi.health.gov.il/api/queries/_batch
         * "{\"requests\":[{\"id\":\"0\",\"queryName\":\"lastUpdate\",\"single\":true,\"parameters\":{}}
         * ,{\"id\":\"1\",\"queryName\":\"infectedPerDate\",\"single\":false,\"parameters\":{}},
         * {\"id\":\"2\",\"queryName\":\"updatedPatientsOverallStatus\",\"single\":false,\"parameters\":{}},
         * {\"id\":\"3\",\"queryName\":\"sickPerDateTwoDays\",\"single\":false,\"parameters\":{}},
         * {\"id\":\"4\",\"queryName\":\"sickPerLocation\",\"single\":false,\"parameters\":{}},
         * {\"id\":\"5\",\"queryName\":\"patientsPerDate\",\"single\":false,\"parameters\":{}},
         * {\"id\":\"6\",\"queryName\":\"deadPatientsPerDate\",\"single\":false,\"parameters\":{}},
         * {\"id\":\"7\",\"queryName\":\"recoveredPerDay\",\"single\":false,\"parameters\":{}},
         * {\"id\":\"8\",\"queryName\":\"testResultsPerDate\",\"single\":false,\"parameters\":{}},
         * {\"id\":\"9\",\"queryName\":\"infectedPerDate\",\"single\":false,\"parameters\":{}},
         * {\"id\":\"10\",\"queryName\":\"patientsPerDate\",\"single\":false,\"parameters\":{}},
         * {\"id\":\"11\",\"queryName\":\"doublingRate\",\"single\":false,\"parameters\":{}},
         * {\"id\":\"12\",\"queryName\":\"infectedByAgeAndGenderPublic\",\"single\":false,\"parameters\":{\"ageSections\":[0,10,20,30,40,50,60,70,80,90]}},
         * {\"id\":\"13\",\"queryName\":\"isolatedDoctorsAndNurses\",\"single\":true,\"parameters\":{}},
         * {\"id\":\"14\",\"queryName\":\"testResultsPerDate\",\"single\":false,\"parameters\":{}},
         * {\"id\":\"15\",\"queryName\":\"contagionDataPerCityPublic\",\"single\":false,\"parameters\":{}},
         * {\"id\":\"16\",\"queryName\":\"hospitalStatus\",\"single\":false,\"parameters\":{}}]}"
         *  ===========done==========
         */
        public function get_Israel(Request $request)
        {
            // $data = ['lastUpdate','infectedPerDate','updatedPatientsOverallStatus','sickPerDateTwoDays','sickPerLocation',
            // 'patientsPerDate','deadPatientsPerDate','recoveredPerDay','testResultsPerDate','infectedPerDate_2','patientsPerDate_2',
            // 'doublingRate','infectedByAgeAndGenderPublic','isolatedDoctorsAndNurses','testResultsPerDate_2','contagionDataPerCityPublic',
            // 'hospitalStatus'];

            // $type = $request->input('type');

            $cacheKey = 'prod.gov.israel_data';

            $response = ApiHelper::gov_Israel($cacheKey);

            return $response;
        }

        // $ch = curl_init();

        // curl_setopt($ch, CURLOPT_URL, 'https://datadashboardapi.health.gov.il/api/queries/_batch');
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"requests\":[{\"id\":\"0\",\"queryName\":\"lastUpdate\",\"single\":true,\"parameters\":{}},{\"id\":\"1\",\"queryName\":\"infectedPerDate\",\"single\":false,\"parameters\":{}},{\"id\":\"2\",\"queryName\":\"updatedPatientsOverallStatus\",\"single\":false,\"parameters\":{}},{\"id\":\"3\",\"queryName\":\"sickPerDateTwoDays\",\"single\":false,\"parameters\":{}},{\"id\":\"4\",\"queryName\":\"sickPerLocation\",\"single\":false,\"parameters\":{}},{\"id\":\"5\",\"queryName\":\"patientsPerDate\",\"single\":false,\"parameters\":{}},{\"id\":\"6\",\"queryName\":\"deadPatientsPerDate\",\"single\":false,\"parameters\":{}},{\"id\":\"7\",\"queryName\":\"recoveredPerDay\",\"single\":false,\"parameters\":{}},{\"id\":\"8\",\"queryName\":\"testResultsPerDate\",\"single\":false,\"parameters\":{}},{\"id\":\"9\",\"queryName\":\"infectedPerDate\",\"single\":false,\"parameters\":{}},{\"id\":\"10\",\"queryName\":\"patientsPerDate\",\"single\":false,\"parameters\":{}},{\"id\":\"11\",\"queryName\":\"doublingRate\",\"single\":false,\"parameters\":{}},{\"id\":\"12\",\"queryName\":\"infectedByAgeAndGenderPublic\",\"single\":false,\"parameters\":{\"ageSections\":[0,10,20,30,40,50,60,70,80,90]}},{\"id\":\"13\",\"queryName\":\"isolatedDoctorsAndNurses\",\"single\":true,\"parameters\":{}},{\"id\":\"14\",\"queryName\":\"testResultsPerDate\",\"single\":false,\"parameters\":{}},{\"id\":\"15\",\"queryName\":\"contagionDataPerCityPublic\",\"single\":false,\"parameters\":{}},{\"id\":\"16\",\"queryName\":\"hospitalStatus\",\"single\":false,\"parameters\":{}}]}");
        // curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        // $headers = array();
        // $headers[] = 'Accept: application/json, text/plain, */*';
        // $headers[] = 'Referer: https://datadashboard.health.gov.il/COVID-19/';
        // $headers[] = 'Origin: https://datadashboard.health.gov.il';
        // $headers[] = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36';
        // $headers[] = 'Dnt: 1';
        // $headers[] = 'Content-Type: application/json';
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // $result = curl_exec($ch);
        // if (curl_errno($ch)) {
        //     echo 'Error:' . curl_error($ch);
        // }
        // curl_close($ch);
        // $fields = array(
        //     'lastUpdate','infectedPerDate','updatedPatientsOverallStatus','sickPerDateTwoDays','sickPerLocation','patientsPerDate',
        //     'deadPatientsPerDate','recoveredPerDay','testResultsPerDate','infectedPerDate','patientsPerDate','doublingRate',
        //     'infectedByAgeAndGenderPublic','isolatedDoctorsAndNurses','testResultsPerDate','contagionDataPerCityPublic','hospitalStatus'
        // );
        // $data = json_decode($result);
        // $lastUpdate_0 = $data[0];
        // $infectedPerDate_1 =$data[1];
        // $updatedPatientsOverallStatus_2 =$data[2];
        // $sickPerDateTwoDays_3 =$data[3];
        // $sickPerLocation_4 =$data[4];
        // $patientsPerDate_5 =$data[5];
        // $deadPatientsPerDate_6 =$data[6];
        // $testResultsPerDate_7 =$data[7];
        // $infectedPerDate_8 =$data[8];
        // $patientsPerDate_9 =$data[9];
        // $doublingRate_10 =$data[10];
        // $infectedByAgeAndGenderPublic_11 =$data[11];
        // $isolatedDoctorsAndNurses_12 =$data[12];
        // $testResultsPerDate_13 =$data[13];
        // $contagionDataPerCityPublic_14 =$data[14];
        // $hospitalStatus_15 =$data[15];

        // return response()->json($data);

        /**
         * Italy 'https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-regioni/dpc-covid19-ita-regioni-latest.csv'
         * ===========done==========
         */

        public function get_Italy(Request $request)
        {
            $cacheKey = 'prod.gov.italy';

            $response = ApiHelper::gov_Italy($cacheKey);

            return $response;
        }

        /**
         * NewZealand https://www.health.govt.nz/our-work/diseases-and-conditions/covid-19-novel-coronavirus/covid-19-current-situation/covid-19-current-cases
         * ===========done==========
         */

        public function get_NewZealand(Request $request)
        {
            $cacheKey = 'prod.gov.newzealand';

            $response = ApiHelper::gov_NewZealand($cacheKey);

            return $response;
        }

        // $update = new ScraperHelper;
        // $resp = $update->curlUrl("https://www.health.govt.nz/our-work/diseases-and-conditions/covid-19-novel-coronavirus/covid-19-data-and-statistics/covid-19-current-cases");
        // $dom = HtmlDomParser::str_get_html($resp);
        // $fields = ['province', 'active', 'recovered', 'deaths', 'cases', '_'];
        // $table = $dom->findMulti('table'); // [6]
        // $data = '';
        // foreach($table[6]->find('tbody > tr') as $node){
        //     foreach($node->find('td')->text() as $td){
        //         $data .= '"'.$td . '",';
        //     }
        //     $data .= "\n";
        // }
        // dd($data);

        /**
         * Nigeria
         * https://covid19.ncdc.gov.ng/report/
         * ===========done==========
         */

        public function get_Nigeria(Request $request)
        {
            $cacheKey = 'prod.gov.nigeria';

            $response = ApiHelper::gov_Nigeria($cacheKey);

            return $response;
        }

        // $update = new ScraperHelper;
        // $resp = $update->curlUrl("https://covid19.ncdc.gov.ng/report/");
        // $dom = HtmlDomParser::str_get_html($resp);
        // $fields = ['state', 'cases', 'active', 'recovered', 'deaths'];
        // $table = $dom->find('#custom1 > tbody'); // [6]
        // $data = '';
        // foreach($table->find('tr') as $node){
        //     foreach($node->find('td')->text() as $td){
        //         $data .= '"'.$td . '",';
        //     }
        //     $data .= "\n";
        // }
        // dd($data);

        /**
         * SouthAfrica
         * https://github.com/dsfsi/covid19za csv files
         * ===========done==========
         */
        public function get_SouthAfrica(Request $request)
        {
            $data = ['confirmed','deaths','recovered','testing','vaccination','fields_full_forms'];

            $type = $request->input('type');

            switch ($type) {
                case $data[0]:
                    $cacheKey = 'prod.gov.southafrica.confirmed';
                    break;
                case $data[1]:
                    $cacheKey = 'prod.gov.southafrica.deaths';
                    break;
                case $data[2]:
                    $cacheKey = 'prod.gov.southafrica.recovered';
                    break;
                case $data[3]:
                    $cacheKey = 'prod.gov.southafrica.testing';
                    break;
                case $data[4]:
                    $cacheKey = 'prod.gov.southafrica.vaccination';
                    break;
                case $data[5]:
                    $cacheKey = 'prod.gov.southafrica.fullforms';
                    break;

                default:
                $cacheKey = false;
                    break;
            }

            if($cacheKey){
                $response = ApiHelper::gov_southafrica($cacheKey);
            }else{
                $response = ApiHelper::SuccessorFail(400,array('message' => "missing parameter 'type' or invalid values supported values: ".implode(',',$data)));
            }



            return $response;
        }
        /**
         * SouthKorea
         * http://ncov.mohw.go.kr/en/bdBoardList.do?brdId=16&brdGubun=162&dataGubun=&ncvContSeq=&contSeq=&board_id=&gubun=
         * ===========done==========
         */
        public function get_SouthKorea(Request $request)
        {
            $cacheKey = 'prod.gov.southkorea';

            $response = ApiHelper::gov_southkorea($cacheKey);

            return $response;
        }
        // $update = new ScraperHelper;
        // $resp = $update->curlUrl("http://ncov.mohw.go.kr/en/bdBoardList.do?brdId=16&brdGubun=162&dataGubun=&ncvContSeq=&contSeq=&board_id=&gubun=");
        // $dom = HtmlDomParser::str_get_html($resp);
        // $fields = ['city', 'todayCases', 'importedCasesToday', 'localCasesToday', 'cases', 'isolated', 'recovered', 'deaths', 'incidence'];
        // $table = $dom->find('table > tbody');
        // $data = '';
        // foreach($table->find('tr') as $node){
        //     foreach($node->find('th')->text() as $th){
        //         $data .= '"'.$th . '",';
        //     }
        //     foreach($node->find('td')->text() as $td){
        //         $data .= '"'.$td . '",';
        //     }
        //     $data .= "\n";
        // }
        // dd($data);

        /**
         * Switzerland
         * https://raw.githubusercontent.com/openZH/covid_19/master/COVID19_Fallzahlen_CH_total_v2.csv
         * ===========done==========
         */
        public function get_Switzerland(Request $request)
        {
            $cacheKey = 'prod.gov.switzerland';

            $response = ApiHelper::gov_switzerland($cacheKey);

            return $response;
        }

        /**
         * UK
         * ===========done==========
         */

        public function get_UK(Request $request)
        {
            $cacheKey = 'prod.gov.UK';

            $response = ApiHelper::gov_uk($cacheKey);

            return $response;
        }

        // use GuzzleHttp\Client;
        // $client = new Client();
        // $response = $client->get('https://api.coronavirus.data.gov.uk/v1/data?filters=areaName=United%20Kingdom;areaType=overview&structure={%22date%22:%22date%22,%22todayTests%22:%22newTestsByPublishDate%22,%22tests%22:%22cumTestsByPublishDate%22,%22testCapacity%22:%22plannedCapacityByPublishDate%22,%22newCases%22:%22newCasesByPublishDate%22,%22cases%22:%22cumCasesByPublishDate%22,%22hospitalized%22:%22hospitalCases%22,%22usedVentilationBeds%22:%22covidOccupiedMVBeds%22,%22newAdmissions%22:%22newAdmissions%22,%22admissions%22:%22cumAdmissions%22,%22todayDeaths%22:%22newDeaths28DaysByPublishDate%22,%22totalDeaths%22:%22cumDeaths28DaysByPublishDate%22,%22ONSweeklyDeaths%22:%22newOnsDeathsByRegistrationDate%22,%22ONStotalDeaths%22:%22cumOnsDeathsByRegistrationDate%22}');
        // dd(json_decode($response->getBody()->getContents()));

        /**
         * Vietnam
         * https://ncov.moh.gov.vn/ { do soooo much } [ get all paitents and match the data :( ]
         *
         * ===========done==========
         */

        public function get_Vietnam(Request $request)
        {

            $cacheKey = 'prod.gov.Vietnam.stats';

            $response = ApiHelper::gov_vietnam($cacheKey);

            return $response;
        }


}
