<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{

    use RefreshDatabase;
    public function test_create_account_returns_400_for_bad_request(): void
    {
        $response = $this->post('api/public/auth/register', [
            "name"          => "",
            "email"         => "johnpeter@gmail.com",
            "password"      => "password",
            "password_confirmation" => "password",

        ]);
        $response->assertSessionHasErrors('name');

        $response->assertSessionHasErrorsIn('name');
    }

    public function test_create_account_returns_200()
    {


        $response = $this->post('/api/public/auth/register', [
            "name"                          => "John",
            "email"                        => "johnpeter@gmail.com",
            "password"                      => "password",

            "password_confirmation"         => "password",

        ]);

        $this->assertCount(1, User::all());
        $response->assertOk();
    }

    public function test_loginUser_should_return_validation_error_when_required_field_not_provided()
    {
        $response = $this->post('/api/public/auth/login', [
            'email'         => '',
            'password'      => ''
        ]);

        $response->assertSessionHasErrorsIn('email');
        $response->assertSessionHasErrorsIn('password');

    }

    public function test_loginUser_should_generate_an_authentication_token_when_user_exist_and_required_fields_are_provided()
    {

        $this->post('/api/public/auth/register', [
            "name"          => "John",
            "email"         => "johnpeter@gmail.com",
            "password"      => "password",
            "password_confirmation"         => "password",

        ]);

        $response = $this->post('/api/public/auth/login', [
            'email'         => 'johnpeter@gmail.com',
            'password'      => 'password'
        ]);

        $response->assertOk();
        $data = json_decode($response->getContent());

        $this->assertEquals("200", $data->message);
        $this->assertTrue($data->success);

        $this->assertNotNull($data->data->token);
        $this->assertEquals("John", $data->data->name);
        $this->assertEquals("johnpeter@gmail.com", $data->data->email);
        $this->assertNotNull($data->data->id);

    }


}
