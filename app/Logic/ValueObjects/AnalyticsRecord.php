<?php

namespace App\Logic\ValueObjects;

use Carbon\Carbon;

class AnalyticsRecord {

    private $city;
    private $date;
    private $deviceCategory;

    public function __construct($rawData)
    {
        $this->setFormattedDate($rawData, 'Y-m-d H:i')->setCity($rawData)->setDeviceCategory($rawData);
        return $this;
    }

    private function setDate($rawData)
    {
        $this->date = $rawData[0];
        return $this;
    }

    public function getDate() : string
    {
        return $this->date;
    }

    private function setFormattedDate($rawData, $format = '')
    {
        $this->date = $this->format($rawData[0], $format);
        return $this;
    }

    private function setCity($rawData)
    {
        $this->city = $rawData[1];
        return $this;
    }

    public function getCity() : string
    {
        return $this->city;
    }

    private function setDeviceCategory($rawData)
    {
        $this->deviceCategory = $rawData[2];
        return $this;
    }

    public function getDeviceCategory() : string
    {
        return $this->deviceCategory;
    }

    public function format($date, $format = 'Y-m-d')
    {
        return Carbon::parse($date)->format($format);
    }

    public function toArray() : array
    {
        return [
            'city' => $this->city,
            'deviceCategory' => $this->deviceCategory,
            'date' => $this->date
        ];
    }
}