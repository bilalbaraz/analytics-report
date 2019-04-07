<?php

namespace App\Logic\Analytics;


use App\Logic\ValueObjects\AnalyticsRecord;
use App\Models\City;
use App\Models\DeviceCategory;
use App\Models\Record;
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

    public function save()
    {
        $rows = [];

        // List of incoming data from remote source row by row
        foreach ($this->data as $row) {
            // Define a value object and manage it over that class
            $record = new AnalyticsRecord($row);
            $recordArray = $record->toArray();
            array_push($rows, $recordArray);
        }

        $collection = collect($rows);
        // group the data by category and list categories as an array
        $deviceCategories = array_keys($collection->groupBy('deviceCategory')->toArray());
        // group the data by city and list cities as an array
        $cities = array_keys($collection->groupBy('city')->toArray());

        // Before saving analytics data, it saves device categories
        foreach ($deviceCategories as $deviceCategory) {
            DeviceCategory::firstOrCreate(['device_category_name' => $deviceCategory]);
        }

        // Before saving analytics data, it saves cities
        foreach ($cities as $city) {
            City::firstOrCreate(['city_name' => $city]);
        }

        $cityRows = City::all();
        $deviceCategoryRows = DeviceCategory::all();

        // It saves records
        $collection->map(function ($item) use ($cityRows, $deviceCategoryRows) {
            $record = $this->prepareRecord($item, $cityRows, $deviceCategoryRows);
            Record::firstOrCreate($record);
        });
    }


    public function prepareRecord($item, $cityRows, $deviceCategoryRows) : array
    {
        $recordCity = $item['city'];
        $recordDeviceCategory = $item['deviceCategory'];

        $record = [];

        $city = $cityRows->first(function($value, $key) use ($recordCity) {
            return $value->city_name == $recordCity;
        });

        $deviceCategory = $deviceCategoryRows->first(function($value, $key) use ($recordDeviceCategory) {
            return $value->device_category_name == $recordDeviceCategory;
        });

        $record['city_id'] = $city->id;
        $record['device_category_id'] = $deviceCategory->id;
        $record['visit_date'] = $item['date'];
        $record['session'] = $item['sessionCount'];
        $record['visitor'] = $item['visit'];
        $record['pageview'] = $item['pageView'];

        return $record;
    }
}