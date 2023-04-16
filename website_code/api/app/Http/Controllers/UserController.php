<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;


use App\Models\User;

class UserController extends Controller
{

    public function login(Request $request){
        $validator = \Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required', 
        ]);

        if (!$validator->fails()) {
            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json(['status' => "error", 'message' => 'The provided credentials are incorrect.'], 401);
            } else {
                $token = $user->createToken($user->guid)->plainTextToken;
                return response()->json(['status' => "OK", 'access_token' => $token]);
            }   
        } else {
            return response()->json(['status' => "error", 'message' => $validator->errors()], 400);
        }
    }


    public function register(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'first_name' => 'required|max:60',
            'last_name' => 'required|max:60',
            'email' => 'required|unique:users',
            'password' => 'required',
           
        ]);
    
        if (!$validator->fails()) {
            try{
                $user = User::create([
                    'first_name' => request('first_name'),
                    'last_name' => request('last_name'),
                    'email' => request('email'),
                    'password' => bcrypt(request('password')),
                    'guid' => Str::uuid()->toString()
                ]);

                $token = $user->createToken(Str::uuid()->toString())->plainTextToken;
            
                return response()->json(['status' => "OK", 'message' => 'User has been successfully registered.', 'access_token' => $token], 201);
            } catch (Exception $e){
                return response()->json(['status' => "error", 'message' => $e], 400);
            }
        } else {
            return response()->json(['status' => "error", 'message' => $validator->errors()], 400);
        }

    }

    public function DailyForecastSubscribeUnsubscribe(Request $request)
    {
        $user_id = $request->user()->id;
        if($user_id){
            $user = User::where('id', $user_id)->first();

            if($user->send_daily_forecast == 1){
                $user->send_daily_forecast = 0;
                $user->save();
                return response()->json(['status' => "OK", 'message' => "You have successfully unsubscribed from receiving daily forecasts."], 200);
            } else {
                $user->send_daily_forecast = 1;
                $user->save();
                return response()->json(['status' => "OK", 'message' => "You have successfully subscribed to receiving daily forecasts."], 200);
            }

        } else {
            return response()->json(['status' => "error", 'message' => "Unable to determine user."], 404);
        }         
    }

}
