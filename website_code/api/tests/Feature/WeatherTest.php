<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use Faker\Generator as Faker;

use App\Models\City;

class WeatherTest extends TestCase
{
    
    public function test_weather_lookup_for_city(): void
    {
        $city = City::where('name', 'Norwich')->first();

        $response = $this->get('api/weather/now/city/'.$city->guid);
        $response->assertStatus(200);
    }

    public function test_weather_lookup_for_city_fails_when_using_fake_city(): void
    {
        $response = $this->get('api/weather/now/city/'.Str::uuid()->toString());

        $response->assertStatus(404);
    }
}
