<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UuserLoginRequest;
use App\Services\UserService;
use App\Trait\ResponseTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserService $userService;
    use ResponseTrait;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    public function createUser(CreateUserRequest $createUserRequest)
    {
        $this->userService->createUser($createUserRequest);

        $this->sendResponse("User Created Successfully", 204);
    }

    public function loginUser(UuserLoginRequest $request)
    {
        $data = $this->userService->login($request);

        $this->sendResponse($data, 200);
    }

    public function logoutUser(Request $request)
    {
        $this->userService->logout($request);

        $this->sendResponse("Logout successful", 204);
    }
}
