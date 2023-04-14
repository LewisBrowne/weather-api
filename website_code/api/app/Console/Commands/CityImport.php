<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use App\Models\City;


class CityImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'city:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports a list of cities into the cache table.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $response = Http::get('https://raw.githubusercontent.com/datasets/world-cities/master/data/world-cities.csv');

        $cities = explode(PHP_EOL, $response->body());



        if (!$response->successful()) {
            //$this->error("cURL Error #:" . $err);
        } else {
            $cityCount = count($cities) - 1; //remove the header row. 
            $loopCount = 0;
            $insertCount = 0;
            $skipCount = 0;
            $errorCount = 0;

            $this->output->progressStart($cityCount);
            foreach($cities as $city){
                $city = explode(',', $city);

                //skip the header row.
                if($loopCount == 0){
                    $loopCount++;
                    continue;
                }

                if(isset($city[0]) && isset($city[1])){
                    if(City::where('name', $city[0])->first()){
                        $skipCount++;
                    } else {
                        try{
                            $city = City::create([
                                'name' => $city[0],
                                'guid' => Str::uuid()->toString(),
                                'country' => $city[1]
                            ]); 
                            $insertCount++;
                        } catch (Exception $e){
                            $errorCount++;
                        }
                    }
                } else {
                    $errorCount++;
                }
                
                
                $this->output->progressAdvance();
                $loopCount++;
            }

            $value = Cache::rememberForever('city', function () {
                return DB::table('city')->get();
            });

            $this->output->progressFinish();

            $this->info($insertCount.' new cities have been added.');
            $this->info($skipCount.' cities already exist.');
            $this->info($errorCount.' cities were unable to be imported.');

            
        }
    }
}
