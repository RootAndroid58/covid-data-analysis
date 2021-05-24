<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleEvents extends Model
{
    use HasFactory;

    protected $dates = [
        'created_at',
        'updated_at',
        'logged_at',
    ];


    protected $fillable = [
        'command',
        'output',
        'logged_at',
        'created_at',
        'updated_at',
    ];
}
