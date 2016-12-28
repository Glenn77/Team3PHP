<?php

namespace App\Http\Controllers;

use App\Station;

use App\Traits\ExceptionTrait;
use App\Traits\ReturnTrait;
use App\Traits\AddressTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StationController extends Controller
{
    use ExceptionTrait;
    use AddressTrait;
    use ReturnTrait;

    protected $className = 'Station';

    public function index()
    {
        $station = Station::all();
        return response()->json($station);
    }

    public function byID($id)
    {
        $station = Station::find($id);
        if (!empty($station))
            return response()->json($station);

        return $this->beautifyReturn(404);
    }

    public function create(Request $request)
    {
        if ($request->StationID
            && $request->Name
            && $request->CoX
            && $request->CoY
            && $request->LastUpdated
        ) {
            $station = new Station();
            $station->StationID = $request->StationID;
            $station->Name = $request->Name;
            $station->CoX = $request->CoX;
            $station->CoY = $request->CoY;
            $station->LastUpdated = $request->LastUpdated;

            try {
                if ($station->save())
                    return $this->beautifyReturn(200, ['Extra' => 'Created', 'StationID' => $station->StationID]);

                return $this->beautifyReturn(406);
            } catch (\Exception $e) {
                return $this->beautifyReturn(406, ['Error' => $this->beautifyException($e)]);
            }
        }
        return $this->beautifyReturn(400);
    }

    public function createWithAddress(Request $request)
    {
        $createAddressResponse = $this->createNewAddress($request);

        if (is_numeric($createAddressResponse)) {
            $request->request->add(['AddressID' => $createAddressResponse]);
            return $this->create($request);
        } else {
            return $createAddressResponse;
        }
    }

    public function update(Request $request, $id)
    {
        $station = Station::find($id);
        if (!empty($station)) {
            if ($request->Name)
                $station->Name = $request->Name;
            if ($request->CoX)
                $station->CoX = $request->CoX;
            if ($request->CoY)
                $station->CoY = $request->CoY;
            if ($request->LastUpdated)
                $station->LastUpdated = $request->LastUpdated;
            else
                $station->LastUpdated = time();

            if ($station->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }

    public function massUpdateStatus()
    {
        $status = DB::select('SELECT COUNT(DISTINCT StationID) as Count, MAX(LastUpdated) as LastUpdated FROM Station');
        return response()->json($status[0]);
    }

    public function massUpdate(Request $request)
    {

        if (!empty($request->stationList)) {

            $stationList = $request->stationList;

            try
            {
                foreach ($stationList as $station)
                {
                    $myStation = Station::find($station['stationID']);

                    if (empty($myStation))
                        $myStation = New Station();

                    $myStation->StationID = $station['stationID'];
                    $myStation->Name = $station['name'];
                    $myStation->CoX = $station['name'];
                    $myStation->CoY = $station['name'];
                    $myStation->LastUpdated = $station['lastUpdated'];

                    if (!$myStation->save())
                        return $this->beautifyReturn(460, ['Extra' => 'MassUpdate']);

                }
                return $this->beautifyReturn(200, ['Extra' => 'MassUpdated']);
            }
            catch (\Exception $e)
            {
                return $this->beautifyReturn(444, ['Error' => $this->beautifyException($e)]);
            }
        }

        return $this->beautifyReturn(400);
    }

    public function delete($id)
    {
        $station = Station::find($id);
        if (!empty($station)) {
            if ($station->delete())
                return $this->beautifyReturn(200, ['Extra' => 'Deleted']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }

    public function stationsAutocomplete()
    {
        $stationNames = [];
        $path = storage_path('xmls/stations.xml');

        $stationsXMLElement = simplexml_load_file($path);

        foreach ($stationsXMLElement->Station as $station) {
            $stationNames[] = (string)$station->Name;
        }

        return $stationNames;
    }
}
