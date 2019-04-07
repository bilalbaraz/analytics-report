<?php

namespace App\Http\Controllers;

use App\Logic\Analytics\Analyzer;
use App\Logic\ValueObjects\AnalyticsRecord;
use App\Models\City;
use App\Models\DeviceCategory;
use Carbon\Carbon;
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
            ->retrieve()
            ->getData();


        $data = [];

        // List of incoming data from remote source row by row
        foreach ($analytics as $row) {
            dump($row);
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
            $itemExists = DeviceCategory::where(['device_category_name' => $deviceCategory])->count();
            if ($itemExists == 0) {
                DeviceCategory::create([
                    'device_category_name' => $deviceCategory
                ]);
            }
        }

        // Before saving analytics data, it saves cities
        foreach ($cities as $city) {
            $itemExists = City::where(['city_name' => $city])->count();
            if ($itemExists == 0) {
                City::create([
                    'city_name' => $city
                ]);
            }
        }
    }
}
