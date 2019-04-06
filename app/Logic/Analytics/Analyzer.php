<?php

namespace App\Logic\Analytics;

use Illuminate\Support\Str;

class Analyzer {

    protected $driver;
    protected $instance;

    public function __construct($driver = '')
    {
        $this->driver = Str::ucfirst(Str::lower(env('ANALYTICS_SERVICE')));
        $this->instance = $this->driverWithNamespace();
    }

    private function driverWithNamespace()
    {
        $fullPath = 'App\\Logic\\Analytics\\' . $this->driver . 'Analytics';
        return new $fullPath();
    }

    public function getInstance()
    {
        return $this->instance;
    }

    public function setDriver($driver) : Analyzer
    {
        $this->driver = $driver;
        return $this;
    }
}