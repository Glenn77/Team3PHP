<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ReturnTrait;
use App\Reservation;

class ReservationController extends Controller
{
    use ReturnTrait;

    /**
     * ClassName to return on BeautifulRespons (Response Trait)
     * @var string
     */
    protected $className = 'Reservation';


    public function index()
    {
        $reservation = Reservation::all();
        return response()->json($reservation);
    }

    public function byID($id)
    {
        $reservation = Reservation::find($id);
        if (!empty($reservation))
            return response()->json($reservation);

        return $this->beautifyReturn(404);
    }

    public function create(Request $request)
    {
        if ($request->ReservationID
            && $request->PassengerCount
            && $request->TrainID
            && $request->Price
            && $request->RouteID
            && $request->LastUpdated
        ) {
            $reservation = new Reservation();
            $reservation->ReservationID = $request->ReservationID;
            $reservation->PassengerCount = $request->PassengerCount;
            $reservation->TrainID = $request->TrainID;
            $reservation->Price = $request->Price;
            $reservation->RouteID = $request->RouteID;
            $reservation->LastUpdated = $request->LastUpdated;

            if ($reservation->save())
                return $this->beautifyReturn(200, ['Extra' => 'Created', 'SubscriptionID' => $reservation->ReservationID]);

            return $this->beautifyReturn(406);
        }
        return $this->beautifyReturn(400);
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::find($id);
        if (!empty($reservation)) {
            if ($request->PassengerCount)
                $reservation->PassengerCount = $request->PassengerCount;
            if ($request->TrainID)
                $reservation->TrainID = $request->TrainID;
            if ($request->Price)
                $reservation->Price = $request->Price;
            if ($request->RouteID)
                $reservation->RouteID = $request->RouteID;
            if ($request->LastUpdated)
                $reservation->LastUpdated = $request->LastUpdated;
            else
                $reservation->LastUpdated = time();

            if ($reservation->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }

    public function delete($id)
    {
        $reservation = Reservation::find($id);
        if (!empty($reservation)) {
            if ($reservation->delete())
                return $this->beautifyReturn(200, ['Extra' => 'Deleted']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }
}
