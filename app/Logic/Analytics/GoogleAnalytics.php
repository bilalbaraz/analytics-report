<?php

namespace App\Logic\Analytics;


use Spatie\Analytics\AnalyticsFacade;
use Spatie\Analytics\Period;

class GoogleAnalytics implements AnalyticsInterface
{
    private $data = [];
    private $options = [];

    public function setOptions($options = [])
    {
        $this->options = $options;
        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function retrieve()
    {
        $analyticsData = AnalyticsFacade::performQuery(
            Period::years(1),
            'ga:sessions',
            [
                'metrics' => $this->options['metrics'],
                'dimensions' => $this->options['dimensions']
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