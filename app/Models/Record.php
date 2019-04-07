<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $fillable = [
        'device_category_name',
        'city_id',
        'pageview',
        'visitor',
        'session'
    ];
}