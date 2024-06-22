<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    use HasUuids;
    protected $fillable =[
        'title',
        'description',
        'due_date',
        'status'
    ];

    public function  user(){
        return $this->belongsTo(User::class);
    }
}
