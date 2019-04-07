<?php

namespace App\Logic\ValueObjects;

use Carbon\Carbon;

class AnalyticsRecord {

    private $city;
    private $date;
    private $deviceCategory;
    private $sessionCount;
    private $visit;
    private $pageView;

    public function __construct($rawData)
    {
        $this
            ->setFormattedDate($rawData, 'Y-m-d H:i')
            ->setCity($rawData)
            ->setDeviceCategory($rawData)
            ->setSessionCount($rawData)
            ->setVisit($rawData)
            ->setPageView($rawData);
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

    private function setSessionCount($rawData)
    {
        $this->sessionCount = $rawData[3];
        return $this;
    }

    public function getSessionCount() : int
    {
        return $this->sessionCount;
    }

    private function setVisit($rawData)
    {
        $this->visit = $rawData[4];
        return $this;
    }

    public function getVisit() : int
    {
        return $this->visit;
    }

    private function setPageView($rawData)
    {
        $this->pageView = $rawData[5];
        return $this;
    }

    public function getPageView() : int
    {
        return $this->pageView;
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
            'date' => $this->date,
            'sessionCount' => $this->sessionCount,
            'visit' => $this->visit,
            'pageView' => $this->pageView
        ];
    }
}