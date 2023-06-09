<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Faker\Generator as Faker;


class UserTest extends TestCase
{  

    public function test_user_can_register(): void
    {
        
        $faker = \Faker\Factory::create();

        $response = $this->post('/api/user/register', ['first_name' => $faker->firstName(), 'last_name' => $faker->lastName(), 'email' => $faker->safeEmail(), 'password' => $faker->password()]);
        $response->assertStatus(201);
    }

    public function test_user_register_validation_fails_when_missing_data(): void
    {
        $response = $this->post('/api/user/register', ['first_name' => 'Sally', 'last_name' => 'test']);
        $response->assertStatus(400);
    }

    public function test_user_can_login(): void
    {
        $faker = \Faker\Factory::create();
        $email_address = $faker->safeEmail();
        $password = $faker->password();
        $response = $this->post('/api/user/register', ['first_name' => $faker->firstName(), 'last_name' => $faker->lastName(), 'email' => $email_address, 'password' => $password]);

        $response = $this->post('/api/user/login', ['email' => $email_address, 'password' => $password]);
        $response->assertStatus(200);
    }

    public function test_user_login_validation_fails_when_using_invalid_data(): void
    {
        $faker = \Faker\Factory::create();

        $response = $this->post('/api/user/login', ['email' => $faker->safeEmail(), 'password' => $faker->password()]);
        $response->assertStatus(401);
    }

    public function test_user_login_validation_fails_when_missing_data(): void
    {
        $faker = \Faker\Factory::create();

        $response = $this->post('/api/user/login', ['email' => $faker->safeEmail()]);
        $response->assertStatus(400);
    }

    public function test_user_can_subscribe_to_daily_forecast(): void
    {
        $faker = \Faker\Factory::create();
        $email_address = $faker->safeEmail();
        $password = $faker->password();

        $registration = $this->postJson('/api/user/register', ['first_name' => $faker->firstName(), 'last_name' => $faker->lastName(), 'email' => $email_address, 'password' => $password]);
        $auth_token = $registration->decodeResponseJson()['access_token'];
        

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$auth_token,
        ])->put('/api/user/dailyforecast/'); 

        $response->assertStatus(200);
    }

    public function test_user_cant_subscribe_to_daily_forecast_when_not_logged_in(): void
    {
        $faker = \Faker\Factory::create();
        
        $response = $this->putJson('/api/user/dailyforecast/'); 

        $response->assertUnauthorized();
    }
}
