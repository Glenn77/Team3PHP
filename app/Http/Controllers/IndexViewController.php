<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;

class IndexViewController extends Controller
{
    public function getDienstRegeling(Request $request)
    {

        $dateArray = explode('-', $request->Date);
        $date = $dateArray[2] . $dateArray[1] . substr($dateArray[0], 2);
        $time = str_replace(':', '', $request->Time);


        $url = sprintf('https://api.irail.be/connections/?format=json&to=%s&from=%s&date=%s&time=%s&timeSel=%s', $request->To, $request->From, $date, $time, $request->TimeSel);

        try
        {

            $client = new \GuzzleHttp\Client();
            $res = $client->get($url);

            $body = json_decode($res->getBody());
            $dienstRegeling = $body->connection;

            return view('pages.index', array('error' => false, 'dienstRegelingen' => $dienstRegeling));

        } catch (RequestException $e) {
                return view('pages.index', array('error' => true));
        }

    }

    public function show()
    {
        return view('pages.index');
    }
}
