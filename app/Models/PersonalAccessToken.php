<?php

namespace App\Models;

use App\Trait\GenerateUUIDTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use HasFactory;
    public $incrementing = false;

    protected $keyType = 'string';

    use GenerateUUIDTrait;
}
