<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Models\City;


class CityController extends Controller
{
    public function list(){
            return response()->json(['status' => "OK", 'data' => Cache::get('city')], 200);
    }

    public function search(Request $request, $query){
        $results = Cache::get('city');

        $results =  collect($results);
        //$results = $results->where('name', 'like', $query); 
        //$results = $results->search($query);
        $results = $results->filter(function($city) use ($query) {
            return stripos($city->name,$query) !== false;
        });

        //Fallback to non cached city table
        if(count($results) == 0){
            $results = City::all();

            $results =  collect($results);
            //$results = $results->where('name', 'like', $query); 
            //$results = $results->search($query);
            $results = $results->filter(function($city) use ($query) {
                return stripos($city->name,$query) !== false;
            });
        }

        if(count($results) == 0){
            return response()->json(['status' => "OK", 'message' => 'Searching for: '.$query, 'results' => null], 404);
        } else {
            return response()->json(['status' => "OK", 'message' => 'Searching for: '.$query,'results' => $results], 200);
        }       

    }
}
