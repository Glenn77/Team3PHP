<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StationsController extends Controller
{
    public function getStationOrStation(Request $request)
    {

        //https://api.irail.be/connections/?to={station1}&from={station2} (OPTIONAL:) &date={dmy}&time=2359&timeSel=arrive or depart

        $dateArray = explode('-', $request->Date);
        $date = $dateArray[2] . $dateArray[1] . substr($dateArray[0], 2);
        $time = str_replace(':', '', $request->Time);

        $url = "https://api.irail.be/liveboard/?format=json";

        if ($request->SearchType == "Station")
            $url .= "&station=" . $request->Name;

        if (isset($request->Date))
            $url .= "&date=" . $date;

        if (isset($request->Time))
            $url .= "&time=" . $time;

        $url .= "&arrdep=" . $request->TimeSel;



        $client = new \GuzzleHttp\Client();
        $res = $client->get($url);

        if($res->getStatusCode() != 200)
            return view('pages.index', array('error' => true));


        $body = json_decode($res->getBody());
        $station = $body->stationinfo;
        $arrivals = $body->arrivals;

        return view('pages.index', array('error' => false, 'station' => $station, 'arrivals' => $arrivals));

    }

    public function show()
    {
        return view('pages.stations');
    }
}
