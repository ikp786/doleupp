<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelUserActivity\Traits\Loggable;

class City extends Model
{
    use HasFactory, Loggable;

    protected $casts = [
        'created_at'  => 'datetime:m/d/Y H:i A',
        'updated_at' => 'datetime:m/d/Y H:i A',
    ];
}
