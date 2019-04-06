<?php

namespace App\Logic\Analytics;


use Spatie\Analytics\AnalyticsFacade;
use Spatie\Analytics\Period;

class GoogleAnalytics implements AnalyticsInterface
{
    private $data = [];

    public function retrieve()
    {
        $analyticsData = AnalyticsFacade::performQuery(
            Period::years(1),
            'ga:sessions',
            [
                'metrics' => 'ga:sessions, ga:pageviews',
                'dimensions' => 'ga:dateHourMinute, ga:city, ga:deviceCategory, ga:sessionCount'
            ]
        );

        return $this->setData($analyticsData);
    }

    private function setData($analyticsData)
    {
        $this->data = $analyticsData;
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }
}