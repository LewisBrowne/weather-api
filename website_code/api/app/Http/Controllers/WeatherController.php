<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;


use App\Models\City;

class WeatherController extends Controller
{
    public function cityWeatherNow(Request $request, $guid){
        $city = City::where('guid', $guid)->first();        
        if(isset($city->name)){
            $response = Http::get('https://api.openweathermap.org/data/2.5/weather?q='.$city->name.'&units=metric&appid=0ecf53556cedca517762e633e2d84728');

            return response()->json(['status' => "OK", 'message' => 'Showing weather for: '.$city->name, 'data' => json_decode($response->body())], 200);
        } else {
            return response()->json(['status' => "OK", 'message' => 'City does not exist.'], 404);
        }
        
    }

    public function coordWeatherNow(Request $request){
        //todo add validation
        $validator = \Validator::make($request->all(), [
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
        ]);

        if (!$validator->fails()) {
            try{
                $response = Http::get('https://api.openweathermap.org/data/2.5/weather?lat='.request('lat').'&lon='.request('long').'&appid=0ecf53556cedca517762e633e2d84728');

                if($response->body() !== null && json_decode($response->body())->cod == 200){
                    return response()->json(['status' => "OK", 'message' => 'Showing weather for: '.request('lat').' , '.request('long'), 'data' => json_decode($response->body())], 200);
                } else {
                    return response()->json(['status' => "error", 'message' => 'Unable to determine weather from these co-ordinates.'], 404);    
                }
                
            } catch (Exception $e){
                return response()->json(['status' => "error", 'message' => $e], 400);
            }
        } else {
            return response()->json(['status' => "error", 'message' => $validator->errors()], 400);
        }        
    }

    // public function cityWeatherHistory(){
    //     return response()->json(['status' => "OK", 'message' => 'Call weather API to get weather for NOW', 200]);
    // }
}
