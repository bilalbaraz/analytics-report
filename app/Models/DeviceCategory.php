<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceCategory extends Model
{
    protected $fillable = [
        'device_category_name'
    ];

    public function records()
    {
        return $this->hasMany(Record::class);
    }
}
