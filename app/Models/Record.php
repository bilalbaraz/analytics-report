<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $fillable = [
        'device_category_id',
        'city_id',
        'visit_date',
        'pageview',
        'visitor',
        'session'
    ];
}