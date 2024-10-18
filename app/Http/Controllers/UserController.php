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

    /**
     * Create User
     * @param CreateUserRequest $createUserRequest
     * @return \Illuminate\Http\JsonResponse
     */

    public function createUser(CreateUserRequest $createUserRequest)
    {
        $this->userService->createUser($createUserRequest);

        return $this->sendResponse("User Created Successfully", 204);
    }

    /**
     * Login user
     * @param UuserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\UnAuthorizedException
     */
    public function loginUser(UuserLoginRequest $request)
    {
        $data = $this->userService->login($request);

        return $this->sendResponse($data, 200);
    }

    /**
     * logout user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logoutUser(Request $request)
    {
        $this->userService->logout($request);

        return $this->sendResponse("Logout successful", 204);
    }
}
