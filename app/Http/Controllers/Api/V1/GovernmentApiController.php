<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class GovernmentApiController extends Controller
{
    //

    public function gov(Request $request,$country)
    {
        $gov = array('Austria');
        $request->validate([
            'country' => 'required|in:'.implode(',',$gov),
        ]);

        switch ($country) {
            case 'Austria':
                $response = $this->get_Austria($request);
                break;

            default:
                $response = ApiHelper::SuccessorFail(200,['error' => 'Supported Countries'. implode(',',$gov)]);
                break;
        }
        return redirect()->json($response);
    }

        /**
         * Austria Gov data
         * ===========done==========
         */
        // use ZanySoft\Zip\ZipManager;
        // use Zip;
        // use GuzzleHttp\Client;
        // $url = 'https://covid19-dashboard.ages.at/data/data.zip';
        // Storage::disk('cron_temp')->delete('getAustria.zip');
        // $guzzle = new Client();
        // $response = $guzzle->get($url);
        // Storage::disk('cron_temp')->put('getAustria.zip', $response->getBody());
        // $path = Storage::path('getAustria.zip');
        // $manager = new ZipManager();
        // $manager->addZip( Zip::open($path) );
        // $list = $manager->listZips();
        // $zip = Zip::open(storage_path('cron_temp\\getAustria.zip'));
        // $is_valid = Zip::check(storage_path('cron_temp\\getAustria.zip'));
        // $zip->extract(storage_path('cron_temp\\zips\\getAustria'));
        // \File::deleteDirectory(storage_path('cron_temp\\zips'));
        // dd($zip,$is_valid);
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
                $response = ApiHelper::gov_Austria($cacheKey);
            }else{
                $response = array('message' => "Supported parameters ".implode(',',$data));
            }

            return response()->json($response);

        }

        /**
         * Canada https://health-infobase.canada.ca/src/data/covidLive/covid19.csv
         * ===========done==========
         */
        public function get_Canada(Request $request)
        {

        }

         /**
          * Colombia https://www.datos.gov.co/api/views/gt2j-8ykr/rows.json
          * ===========done==========
          */
        public function get_Colombia(Request $request)
        {

        }

        /**
         *  Germany https://www.rki.de/DE/Content/InfAZ/N/Neuartiges_Coronavirus/Fallzahlen.html
         * ===========done==========
         */
        public function get_Germany(Request $request)
        {

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

        }

        /**
         * Israel https://datadashboardapi.health.gov.il/api/queries/_batch
         * "{\"requests\":[{\"id\":\"0\",\"queryName\":\"lastUpdate\",\"single\":true,\"parameters\":{}},{\"id\":\"1\",\"queryName\":\"infectedPerDate\",\"single\":false,\"parameters\":{}},{\"id\":\"2\",\"queryName\":\"updatedPatientsOverallStatus\",\"single\":false,\"parameters\":{}},{\"id\":\"3\",\"queryName\":\"sickPerDateTwoDays\",\"single\":false,\"parameters\":{}},{\"id\":\"4\",\"queryName\":\"sickPerLocation\",\"single\":false,\"parameters\":{}},{\"id\":\"5\",\"queryName\":\"patientsPerDate\",\"single\":false,\"parameters\":{}},{\"id\":\"6\",\"queryName\":\"deadPatientsPerDate\",\"single\":false,\"parameters\":{}},{\"id\":\"7\",\"queryName\":\"recoveredPerDay\",\"single\":false,\"parameters\":{}},{\"id\":\"8\",\"queryName\":\"testResultsPerDate\",\"single\":false,\"parameters\":{}},{\"id\":\"9\",\"queryName\":\"infectedPerDate\",\"single\":false,\"parameters\":{}},{\"id\":\"10\",\"queryName\":\"patientsPerDate\",\"single\":false,\"parameters\":{}},{\"id\":\"11\",\"queryName\":\"doublingRate\",\"single\":false,\"parameters\":{}},{\"id\":\"12\",\"queryName\":\"infectedByAgeAndGenderPublic\",\"single\":false,\"parameters\":{\"ageSections\":[0,10,20,30,40,50,60,70,80,90]}},{\"id\":\"13\",\"queryName\":\"isolatedDoctorsAndNurses\",\"single\":true,\"parameters\":{}},{\"id\":\"14\",\"queryName\":\"testResultsPerDate\",\"single\":false,\"parameters\":{}},{\"id\":\"15\",\"queryName\":\"contagionDataPerCityPublic\",\"single\":false,\"parameters\":{}},{\"id\":\"16\",\"queryName\":\"hospitalStatus\",\"single\":false,\"parameters\":{}}]}"
         *  ===========done==========
         */

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

        /**
         * NewZealand https://www.health.govt.nz/our-work/diseases-and-conditions/covid-19-novel-coronavirus/covid-19-current-situation/covid-19-current-cases
         * ===========done==========
         */

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

        /**
         * SouthKorea
         * http://ncov.mohw.go.kr/en/bdBoardList.do?brdId=16&brdGubun=162&dataGubun=&ncvContSeq=&contSeq=&board_id=&gubun=
         * ===========done==========
         */
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

        /**
         * UK
         * ===========done==========
         */

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

}
