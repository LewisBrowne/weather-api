<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\City;

class CityTest extends TestCase
{       
    public function test_city_import_command(): void
    {
        $this->artisan('city:import')->assertSuccessful();
    }

    public function test_city_list_returns(): void
    {
        $response = $this->get('/api/city');
        $response->assertStatus(200);
    }

    public function test_city_search_returns(): void
    {
        $this->artisan('city:import');
        
        $city = City::where('name', 'Norwich')->first();

        $response = $this->get('/api/city/Norwich');
        $response->assertStatus(200);
    }

    public function test_city_search_no_results(): void
    {
        $response = $this->get('/api/city/fakecityname');

        $response->assertStatus(404);
    }
}
