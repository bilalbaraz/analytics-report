<?php

namespace App\Http\Controllers;

use App\Logic\Analytics\Analyzer;
use App\Models\Record;
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
        $records = Record::with([
            'city',
            'deviceCategory'
        ])->paginate(10);

        return view('home', compact('records'));
    }

    public function collect(Request $request)
    {
        $analyzer = new Analyzer();
        $analyzer
            ->getInstance()
            ->setOptions([
                'metrics' => 'ga:sessions, ga:pageviews',
                'dimensions' => 'ga:dateHourMinute, ga:city, ga:deviceCategory, ga:sessionCount'
            ])
            ->retrieve()
            ->save();
    }
}
