<?php

namespace App\Http\Controllers;

use App\Route;
use App\RouteWithStation;
use App\Traits\ExceptionTrait;
use App\Traits\ReturnTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RouteController extends Controller
{
    use ExceptionTrait;
    use ReturnTrait;

    protected $className = 'Route';

    public function index()
    {
        $route = RouteWithStation::all();
        return response()->json($route);
    }

    public function byID($id)
    {
        $route = RouteWithStation::find($id);
        if (!empty($route))
            return response()->json($route);

        return $this->beautifyReturn(404);
    }

    public function byStations($departureStationID, $arrivalStationID)
    {

        $route = Route::where('ArrivalStationID', '=', $arrivalStationID)
                        ->where('DepartureStationID', '=', $departureStationID)
                        ->first();
                        


        if(!empty($route) && $route->count())
            return response()->json($route);

        return $this->beautifyReturn(404);

    }

    public function create(Request $request)
    {
        if ( $request->RouteID
            && $request->DepartureStationID
            && $request->ArrivalStationID
            && $request->LastUpdated
        ) {
            $route = new Route();
            $route->RouteID = $request->RouteID;
            $route->DepartureStationID = $request->DepartureStationID;
            $route->ArrivalStationID = $request->ArrivalStationID;
            $route->LastUpdated = $request->LastUpdated;

            try {
                if ($route->save())
                    return $this->beautifyReturn(200, ['Extra' => 'Created', 'RouteID' => $route->RouteID]);

                return $this->beautifyReturn(406);
            } catch (\Exception $e) {
                return $this->beautifyReturn(406, ['Error' => $this->beautifyException($e)]);
            }
        }
        return $this->beautifyReturn(400);
    }

    public function update(Request $request, $id)
    {
        $route = Route::find($id);
        if (!empty($route)) {
            if ($request->DepartureStationID)
                $route->DepartureStationID = $request->DepartureStationID;
            if ($request->ArrivalStationID)
                $route->ArrivalStationID = $request->ArrivalStationID;
            if ($request->LastUpdated)
                $route->LastUpdated = $request->LastUpdated;
            else
                $route->LastUpdated = time();


            if ($route->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }

    public function massUpdateStatus()
    {
        $status = DB::select('SELECT COUNT(DISTINCT RouteID) as Count, MAX(LastUpdated) as LastUpdated FROM Route');
        return response()->json($status[0]);
    }

    public function massUpdate(Request $request)
    {

        if (!empty($request->RouteList)) {

            $routeList = $request->RouteList;

            try
            {
                foreach ($routeList as $route)
                {
                    $myRoute = Route::find($route['RouteID']);

                    if (empty($myRoute))
                        $myRoute = New Route();

                    $myRoute->RouteID = $route['RouteID'];
                    $myRoute->DepartureStationID = $route['DepartureStationID'];
                    $myRoute->ArrivalStationID = $route['ArrivalStationID'];
                    $myRoute->LastUpdated = $route['LastUpdated'];

                    if (!$myRoute->save())
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
        $route = Route::find($id);
        if (!empty($route)) {
            if ($route->delete())
                return $this->beautifyReturn(200, ['Extra' => 'Deleted']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }
}
