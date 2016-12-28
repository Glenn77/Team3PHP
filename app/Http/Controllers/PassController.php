<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ReturnTrait;
use App\Pass;
use Illuminate\Support\Facades\DB;

class PassController extends Controller
{
    use ReturnTrait;

    /**
     * ClassName to return on BeautifulRespons (Response Trait)
     * @var string
     */
    protected $className = 'Pass';


    public function index()
    {
        $pass = Pass::all();
        return response()->json($pass);
    }

    public function byID($id)
    {
        $pass = Pass::find($id);
        if (!empty($pass))
            return response()->json($pass);

        return $this->beautifyReturn(404);
    }

    public function create(Request $request)
    {
        if ($request->PassID
            && $request->TypePassID
            && $request->Date
            && $request->StartDate
            && $request->ComfortClass
            && $request->LastUpdated
        ) {
            $pass = new Pass();
            $pass->PassID = $request->PassID;
            $pass->TypePassID = $request->TypePassID;
            $pass->Date = $request->Date;
            $pass->StartDate = $request->StartDate;
            $pass->ComfortClass = $request->ComfortClass;
            $pass->LastUpdated = $request->LastUpdated;

            if ($pass->save())
                return $this->beautifyReturn(200, ['Extra' => 'Created', 'SubscriptionID' => $pass->PassID]);

            return $this->beautifyReturn(406);
        }
        return $this->beautifyReturn(400);
    }

    public function update(Request $request, $id)
    {
        $pass = Pass::find($id);
        if (!empty($pass)) {
            if ($request->TypePassID)
                $pass->TypePassID = $request->TypePassID;
            if ($request->Date)
                $pass->Date = $request->Date;
            if ($request->StartDate)
                $pass->StartDate = $request->StartDate;
            if ($request->ComfortClass)
                $pass->ComfortClass = $request->ComfortClass;
            if ($request->LastUpdated)
                $pass->LastUpdated = $request->LastUpdated;
            else
                $pass->LastUpdated = time();

            if ($pass->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }

    public function massUpdateStatus()
    {
        $status = DB::select('SELECT COUNT(DISTINCT PassID) as Count, MAX(LastUpdated) as LastUpdated FROM Pass');
        return response()->json($status[0]);
    }

    public function massUpdate(Request $request)
    {

        if (!empty($request->PassList)) {

            $passList = $request->PassList;

            try
            {
                foreach ($passList as $pass)
                {
                    $myPass = Pass::find($pass['PassID']);

                    if (empty($myPass))
                        $myPass = New Pass();

                    $myPass->PassID = $pass['PassID'];
                    $myPass->TypePassID = $pass['TypePassID'];
                    $myPass->Date = $pass['Date'];
                    $myPass->StartDate = $pass['StartDate'];
                    $myPass->ComfortClass = $pass['ComfortClass'];
                    $myPass->LastUpdated = $pass['LastUpdated'];

                    if (!$myPass->save())
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
        $pass = Pass::find($id);
        if (!empty($pass)) {
            if ($pass->delete())
                return $this->beautifyReturn(200, ['Extra' => 'Deleted']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }
}
