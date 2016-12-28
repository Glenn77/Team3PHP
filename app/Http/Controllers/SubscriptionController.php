<?php

namespace App\Http\Controllers;

use App\Subscription;
use App\Traits\ReturnTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    use ReturnTrait;

    /**
     * ClassName to return on BeautifulRespons (Response Trait)
     * @var string
     */
    protected $className = 'Subscription';


    public function index()
    {
        $subscription = Subscription::all();
        return response()->json($subscription);
    }

    public function byID($id)
    {
        $subscription = Subscription::find($id);
        if (!empty($subscription))
            return response()->json($subscription);

        return $this->beautifyReturn(404);
    }

    public function create(Request $request)
    {
        if ($request->SubscriptionID
            && $request->RailCardID
            && $request->RouteID
            && $request->DiscountID
            && $request->ValidFrom
            && $request->ValidUntil
            && $request->LastUpdated
        ) {
            $subscription = new Subscription();
            $subscription->SubscriptionID = $request->SubscriptionID;
            $subscription->RailCardID = $request->RailCardID;
            $subscription->RouteID = $request->RouteID;
            $subscription->DiscountID = $request->DiscountID;
            $subscription->ValidFrom = $request->ValidFrom;
            $subscription->ValidUntil = $request->ValidUntil;
            $subscription->LastUpdated = $request->LastUpdated;

            if ($subscription->save())
                return $this->beautifyReturn(200, ['Extra' => 'Created', 'SubscriptionID' => $subscription->SubscriptionID]);

            return $this->beautifyReturn(406);
        }
        return $this->beautifyReturn(400);
    }

    public function update(Request $request, $id)
    {
        $subscription = Subscription::find($id);
        if (!empty($subscription)) {
            if ($request->RailCardID)
                $subscription->RailCardID = $request->RailCardID;
            if ($request->RouteID)
                $subscription->RouteID = $request->RouteID;
            if ($request->DiscountID)
                $subscription->DiscountID = $request->DiscountID;
            if ($request->ValidFrom)
                $subscription->ValidFrom = $request->ValidFrom;
            if ($request->ValidUntil)
                $subscription->ValidUntil = $request->ValidUntil;
            if ($request->LastUpdated)
                $subscription->LastUpdated = $request->LastUpdated;
            else
                $subscription->LastUpdated = time();

            if ($subscription->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }

    public function massUpdateStatus()
    {
        $status = DB::select('SELECT COUNT(DISTINCT SubscriptionID) as Count, MAX(LastUpdated) as LastUpdated FROM Subscription');
        return response()->json($status[0]);
    }

    public function massUpdate(Request $request)
    {

        if (!empty($request->SubscriptionList)) {

            $subscriptionList = $request->SubscriptionList;

            try
            {
                foreach ($subscriptionList as $subscription)
                {
                    $mySubscription = Subscription::find($subscription['SubscriptionID']);

                    if (empty($mySubscription))
                        $mySubscription = New Subscription();

                    $mySubscription->SubscriptionID = $subscription['SubscriptionID'];
                    $mySubscription->RailCardID = $subscription['RailCardID'];
                    $mySubscription->RouteID = $subscription['RouteID'];
                    $mySubscription->DiscountID = $subscription['DiscountID'];
                    $mySubscription->ValidFrom = $subscription['ValidFrom'];
                    $mySubscription->ValidUntil = $subscription['ValidUntil'];
                    $mySubscription->LastUpdated = $subscription['LastUpdated'];

                    if (!$mySubscription->save())
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
        $subscription = Subscription::find($id);
        if (!empty($subscription)) {
            if ($subscription->delete())
                return $this->beautifyReturn(200, ['Extra' => 'Deleted']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }
}
