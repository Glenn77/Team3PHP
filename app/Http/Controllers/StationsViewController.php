<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;

class StationsViewController extends Controller
{
    public function getStation(Request $request)
    {

        $dateArray = explode('-', $request->Date);
        $date = $dateArray[2] . $dateArray[1] . substr($dateArray[0], 2);
        $time = str_replace(':', '', $request->Time);

        $url = "https://api.irail.be/liveboard/?format=json";

        $url .= "&station=" . $request->Name;

        if (isset($request->Date))
            $url .= "&date=" . $date;

        if (isset($request->Time))
            $url .= "&time=" . $time;

        $url .= "&arrdep=" . $request->TimeSel;



        try {
            $client = new \GuzzleHttp\Client();
            $res = $client->get($url);

            $body = json_decode($res->getBody());
            $station = $body->stationinfo;
            $departures = $body->departures;

            return view('pages.stations', array('error' => false, 'station' => $station, 'departures' => $departures));

        } catch (RequestException $e) {
            return view('pages.index', array('error' => true));

        }

    }

    public function show()
    {
        return view('pages.stations');
    }
}
