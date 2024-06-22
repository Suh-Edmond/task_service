<?php

namespace App\Services;

use App\Exceptions\UnAuthorizedException;
use App\Http\Resources\UserResource;
use App\Interfaces\UserInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService implements UserInterface
{

    public function createUser($request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password'   => Hash::make($request->password),
        ]);
    }

    public function login($request)
    {
        $user = User::where('email', $request->email)->firstOrFail();
        if (!Hash::check($request->password, $user->password)) {
            throw new UnAuthorizedException('Bad Credentials', 403);
        }
        $token = $this->generateToken($user);
        return  new UserResource($user, $token);
    }

    public function logout($request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    private function generateToken($user)
    {
        return !is_null($user) ? $user->createToken('access-token', $user->roles->toArray())->plainTextToken : "";
    }
}
