<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'city_name'
    ];

    public function records()
    {
        return $this->hasMany(Record::class);
    }
}
