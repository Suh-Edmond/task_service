<?php

namespace Tests\Unit;



use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use DatabaseMigrations;

    private UserService $userService;
    private Request  $data;

    public function setUp(): void
    {
        parent::setUp();

        $this->userService = new UserService();

        $this->data = new Request(['name'       => 'Test', 'email' => "examole@gmail.com", 'password' => 'password']);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
    public function test_create_account_should_create_account(): void
    {
        $this->userService->createUser($this->data);

        $this->assertDatabaseCount(User::class, 1);
        $this->assertDatabaseHas(User::class, ['name' => $this->data['name']]);
        $this->assertDatabaseHas(User::class, ['email' => $this->data['email']]);
    }

    public function test_login_returns_not_found(): void
    {
        $this->data['email'] = "exampl@gmail.com";
        $this->data['password'] = "password";

        $userMock = $this->getMockBuilder(User::class)->addMethods(['firstOrFail'])->getMock();

        $userMock->expects($this->once())->method('firstOrFail')->will($this->returnValue(null));

        $userMock->firstOrFail();

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Models\User].");

        $this->userService->login($this->data);
    }

    public function test_login_exception_for_password_mismatch(): void
    {
        $this->data['email'] = "exampl@gmail.com";
        $this->data['password'] = "password";
        $user = User::factory([
            'email'  => 'email@gmailcc.om',
            'name'   => 'Test',
            'password'=>'Summer1343'
        ])->create();

        $userMock = $this->getMockBuilder(User::class)->addMethods(['firstOrFail'])->getMock();

        $userMock->expects($this->once())->method('firstOrFail')->will($this->returnValue($user));

        $userMock->firstOrFail();

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Models\User].");

        $this->userService->login($this->data);
    }

    public function test_login_user_should_login_user_when_valid_credentials()
    {
        $user = User::factory([
            'email' => 'email@gmail.com',
            'password' => Hash::make('password')
        ])->create();



        $data = new Request([
            'email'    => 'email@gmail.com',
            'password' => 'password'
        ]);

        $mock = $this->getMockBuilder(User::class)->addMethods(['firstOrFail'])->getMock();
        $mock->expects($this->once())->method('firstOrFail')->willReturn($user);

        $mock->firstOrFail();


        $response = $this->userService->login($data);

        $jsonResponse = json_decode($response->response()->content());

        $this->assertNotNull($jsonResponse->data->token);

        $this->assertEquals($user->name, $jsonResponse->data->name);
        $this->assertEquals($user->email, $jsonResponse->data->email);


    }

}
