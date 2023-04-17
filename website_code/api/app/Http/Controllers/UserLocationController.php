<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

use App\Models\UserLocation;
use App\Models\City;

class UserLocationController extends Controller
{

    public function getLocation(Request $request, $guid){
        if($request->user()->id){
            $location = UserLocation::where('guid', $guid)->first();

            if($location){
                return response()->json(['status' => "OK", 'data' => $location], 200);
            } else {
                return response()->json(['status' => "OK", 'message' => "This location does not exist."], 404);
            }

        } else {
            return response()->json(['status' => "error", 'message' => "Cannot determine user."], 403);
        }
    }

    public function list(Request $request){
        if($request->user()->id){
            $locations = UserLocation::where('user_id', $request->user()->id)->get();

            if(count($locations) >= 1){
                return response()->json(['status' => "OK", 'data' => $locations], 200);
            } else {
                return response()->json(['status' => "OK", 'message' => "You do not have any locations saved."], 404);
            }
            
        } else {
            return response()->json(['status' => "error", 'message' => "Cannot determine user."], 403);
        }
    }

    public function store(Request $request){
        $validator = \Validator::make($request->all(), [
            'city_guid' => 'uuid',
            'lat' => 'numeric',
            'long' => 'numeric',
        ]);

        if($request->user()->id){
            if (!$validator->fails()) {
                if(request('city_guid')){
                    $city = City::where('guid', request('city_guid'))->first();

                    if($city && $city->id){
                        $location = UserLocation::create([
                            'guid' => Str::uuid()->toString(),
                            'user_id' => $request->user()->id,
                            'city_id' => $city->id
                        ]);

                        return response()->json(['status' => "OK", 'message' => $city->name." has been added to your saved locations.", 'location_guid' => $location->guid], 200);        

                    } else {
                        return response()->json(['status' => "error", 'message' => "City does not exist."], 404);        
                    }
                            
                } else {
                    $location = UserLocation::create([
                        'guid' => Str::uuid()->toString(),
                        'user_id' => $request->user()->id,
                        'lat' => request('lat'),
                        'long' => request('long') 
                    ]);

                    return response()->json(['status' => "OK", 'message' => request('lat').",".request('long')." has been added to your saved locations.", 'location_guid' => $location->guid],200);
                }
            } else {
                return response()->json(['status' => "error", 'message' => $validator->errors()], 400);
            }
        } else {
            return response()->json(['status' => "error", 'message' => "Cannot determine user."], 403);
        }
    }

    public function delete(Request $request, $guid){
        if($request->user()->id){
            $location = UserLocation::where('guid', $guid)->where('user_id', $request->user()->id)->first();

            if($location){

                $location->delete();
                return response()->json(['status' => "OK", 'message' => "Location has been deleted."], 200);
            } else {
                return response()->json(['status' => "OK", 'message' => "This location does not exist."], 404);
            }

        } else {
            return response()->json(['status' => "error", 'message' => "Cannot determine user."], 403);
        }
    }    
}
