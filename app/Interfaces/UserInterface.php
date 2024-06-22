<?php

namespace App\Interfaces;

interface  UserInterface {
    public function createUser($request);

    public function login($request);

    public function logout($request);
}
