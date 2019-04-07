<?php

namespace App\Http\Controllers;

use App\Logic\Analytics\Analyzer;
use App\Logic\ValueObjects\AnalyticsRecord;
use App\Models\City;
use App\Models\DeviceCategory;
use App\Models\Record;
use Carbon\Carbon;
use function foo\func;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        return view('home');
    }

    public function collect(Request $request)
    {
        $analyzer = new Analyzer();
        $analytics = $analyzer
            ->getInstance()
            ->setOptions([
                'metrics' => 'ga:sessions, ga:pageviews',
                'dimensions' => 'ga:dateHourMinute, ga:city, ga:deviceCategory, ga:sessionCount'
            ])
            ->retrieve()
            ->getData();


        $data = [];

        // List of incoming data from remote source row by row
        foreach ($analytics as $row) {
            // Define a value object and manage it over that class
            $record = new AnalyticsRecord($row);
            $recordArray = $record->toArray();
            array_push($data, $recordArray);
        }

        $collection = collect($data);
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

            Record::firstOrCreate($record);
        });
    }
}
