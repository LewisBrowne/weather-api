<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserLocationTest extends TestCase
{
        
    public function test_user_location_list_returns_no_results(): void
    {
        $faker = \Faker\Factory::create();
        $email_address = $faker->safeEmail();
        $password = $faker->password();

        $registration = $this->postJson('/api/user/register', ['first_name' => $faker->firstName(), 'last_name' => $faker->lastName(), 'email' => $email_address, 'password' => $password]);
        $auth_token = $registration->decodeResponseJson()['access_token'];
        

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$auth_token,
        ])->get('/api/user/location');

        $response->assertStatus(404);
    }

    public function test_user_save_a_location(): void
    {
        $faker = \Faker\Factory::create();
        $email_address = $faker->safeEmail();
        $password = $faker->password();

        $registration = $this->postJson('/api/user/register', ['first_name' => $faker->firstName(), 'last_name' => $faker->lastName(), 'email' => $email_address, 'password' => $password]);
        $auth_token = $registration->decodeResponseJson()['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$auth_token,
        ])->postJson('/api/user/location', ['lat' => 1, 'long' => 2]);

        $response->assertStatus(200);
    }

    public function test_user_can_load_single_location(): void
    {

        $faker = \Faker\Factory::create();
        $email_address = $faker->safeEmail();
        $password = $faker->password();

        $registration = $this->postJson('/api/user/register', ['first_name' => $faker->firstName(), 'last_name' => $faker->lastName(), 'email' => $email_address, 'password' => $password]);
        $auth_token = $registration->decodeResponseJson()['access_token'];
        
        $location = $this->withHeaders([
            'Authorization' => 'Bearer '.$auth_token,
        ])->postJson('/api/user/location', ['lat' => 1, 'long' => 2]);

        $location = $location->decodeResponseJson()['location_guid'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$auth_token,
        ])->get('/api/user/location/'.$location);

        $response->assertStatus(200);
    }

    public function test_user_can_get_all_locations(): void
    {

        $faker = \Faker\Factory::create();
        $email_address = $faker->safeEmail();
        $password = $faker->password();

        $registration = $this->postJson('/api/user/register', ['first_name' => $faker->firstName(), 'last_name' => $faker->lastName(), 'email' => $email_address, 'password' => $password]);
        $auth_token = $registration->decodeResponseJson()['access_token'];
        
        $location = $this->withHeaders([
            'Authorization' => 'Bearer '.$auth_token,
        ])->postJson('/api/user/location', ['lat' => 1, 'long' => 2]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$auth_token,
        ])->get('/api/user/location');

        $response->assertStatus(200);
    }

    public function test_user_cant_load_another_users_single_location(): void
    {
        
        $faker = \Faker\Factory::create();
        $email_address = $faker->safeEmail();
        $password = $faker->password();

        $registration = $this->postJson('/api/user/register', ['first_name' => $faker->firstName(), 'last_name' => $faker->lastName(), 'email' => $email_address, 'password' => $password]);
        $auth_token = $registration->decodeResponseJson()['access_token'];
        
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$auth_token,
        ])->get('/api/user/location/'.Str::uuid()->toString());

        $response->assertStatus(404);
    }

    public function test_user_can_delete_location(): void
    {
        $faker = \Faker\Factory::create();
        $email_address = $faker->safeEmail();
        $password = $faker->password();

        $registration = $this->postJson('/api/user/register', ['first_name' => $faker->firstName(), 'last_name' => $faker->lastName(), 'email' => $email_address, 'password' => $password]);
        $auth_token = $registration->decodeResponseJson()['access_token'];
        
        $location = $this->withHeaders([
            'Authorization' => 'Bearer '.$auth_token,
        ])->postJson('/api/user/location', ['lat' => 1, 'long' => 2]);

        $location = $location->decodeResponseJson()['location_guid'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$auth_token,
        ])->deleteJson('/api/user/location/'.$location);

        $response->assertStatus(200);
    }

    public function test_user_cannot_delete_another_users_location(): void
    {
        $faker = \Faker\Factory::create();
        $email_address = $faker->safeEmail();
        $password = $faker->password();

        $registration = $this->postJson('/api/user/register', ['first_name' => $faker->firstName(), 'last_name' => $faker->lastName(), 'email' => $email_address, 'password' => $password]);
        $auth_token = $registration->decodeResponseJson()['access_token'];
        
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$auth_token,
        ])->delete('/api/user/location/'.Str::uuid()->toString());

        $response->assertStatus(404);
    }


}
