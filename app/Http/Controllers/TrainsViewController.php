<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Exception\RequestException;

class TrainsViewController extends Controller
{
    public function getTrain(Request $request)
    {
        // https://api.irail.be/vehicle/?id=BE.NMBS.P1234


        $url = sprintf("https://api.irail.be/vehicle/?format=json&id=BE.NMBS.%s", $request->TreinID);

        try
        {
            $client = new \GuzzleHttp\Client();
            $res = $client->get($url);

            $body = json_decode($res->getBody());
            $vehicleinfo = $body->vehicleinfo;
            $stops = $body->stops;

            return view('pages.trains', array('error' => false, 'vehicleinfo' => $vehicleinfo, 'stops' => $stops));

        } catch (RequestException $e) {
            return view('pages.trains', array('error' => true));
        }
    }

    public function show()
    {
        return view('pages.trains');
    }
}
