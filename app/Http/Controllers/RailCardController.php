<?php

namespace App\Http\Controllers;

use App\RailCard;
use App\Traits\ReturnTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RailCardController extends Controller
{
    use ReturnTrait;

    protected $className = 'RailCard';

    public function index()
    {
        $railCard = RailCard::all();
        return response()->json($railCard);
    }

    public function byID($id)
    {
        $railCard = RailCard::find($id);
        if (!empty($railCard))
            return response()->json($railCard);

        return $this->beautifyReturn(404);
    }

    public function create(Request $request)
    {
        if ( $request->CardID
            && $request->LastUpdated
        ) {
            $railCard = new RailCard();
            $railCard->CardID = $request->CardID;
            $railCard->LastUpdated = $request->LastUpdated;

            if ($railCard->save())
                return $this->beautifyReturn(200, ['Extra' => 'Created', 'RailCardID' => $railCard->TypePassID]);

            return $this->beautifyReturn(406);
        }
        return $this->beautifyReturn(400);
    }
    
    public function update(Request $request, $id)
    {
        $railCard = RailCard::find($id);
        if (!empty($railCard)) {
            if ($request->TypePassID)
                $railCard->TypePassID = $request->TypePassID;
            if ($request->LastUpdated)
                $railCard->LastUpdated = $request->LastUpdated;


            if ($railCard->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }

    public function massUpdateStatus()
    {
        $status = DB::select('SELECT COUNT(DISTINCT CardID) as Count, MAX(LastUpdated) as LastUpdated FROM RailCard');
        return response()->json($status[0]);
    }

    public function massUpdate(Request $request)
    {

        if (!empty($request->RailCardList)) {

            $railCardList = $request->RailCardList;

            try
            {
                foreach ($railCardList as $railCard)
                {
                    $myRailCard = RailCard::find($railCard['TypePassID']);

                    if (empty($myRailCard))
                        $myRailCard = New RailCard();

                    $myRailCard->TypePassID = $railCard['CardID'];
                    $myRailCard->LastUpdated = $railCard['LastUpdated'];

                    if (!$myRailCard->save())
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
        $railCard = RailCard::find($id);
        if (!empty($railCard)) {
            if ($railCard->delete())
                return $this->beautifyReturn(200, ['Extra' => 'Deleted']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }
}
