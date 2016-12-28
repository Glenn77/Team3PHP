<?php

namespace App\Http\Controllers;

use App\Discount;
use App\Traits\ReturnTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscountController extends Controller
{
    use ReturnTrait;

    protected $className = 'Discount';

    public function index()
    {
        $discount = Discount::all();
        return response()->json($discount);
    }

    public function byID($id)
    {
        $discount = Discount::find($id);
        if (!empty($discount))
            return response()->json($discount);

        return $this->beautifyReturn(404);
    }

    public function create(Request $request)
    {
        if ($request->DiscountID
            && $request->Name
            && $request->Amount
            && $request->LastUpdated
        ) {
            $discount = new Discount();
            $discount->DiscountID = $request->DiscountID;
            $discount->Name = $request->Name;
            $discount->Amount = $request->Amount;
            $discount->LastUpdated = $request->LastUpdated;

            if ($discount->save())
                return $this->beautifyReturn(200, ['Extra' => 'Created', 'DiscountID' => $discount->DiscountID]);

            return $this->beautifyReturn(406);
        }
        return $this->beautifyReturn(400);
    }

    public function update(Request $request, $id)
    {
        $discount = Discount::find($id);
        if (!empty($discount)) {
            if ($request->Name)
                $discount->Name = $request->Name;
            if ($request->Amount)
                $discount->Amount = $request->Amount;
            if ($request->LastUpdated)
                $discount->LastUpdated = $request->LastUpdated;
            else
                $discount->LastUpdated = time();

            if ($discount->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }

    public function massUpdateStatus()
    {
        $status = DB::select('SELECT COUNT(DISTINCT DiscountID) as Count, MAX(LastUpdated) as LastUpdated FROM Discount');
        return response()->json($status[0]);
    }

    public function massUpdate(Request $request)
    {

        if (!empty($request->DiscountList)) {

            $discountList = $request->DiscountList;

            try
            {
                foreach ($discountList as $discount)
                {
                    $myDiscount = Discount::find($discount['DiscountID']);

                    if (empty($myCustomer))
                        $myDiscount = New Discount();

                    $myDiscount->DiscountID = $discount['DiscountID'];
                    $myDiscount->Name = $discount['Name'];
                    $myDiscount->Amount = $discount['Amount'];
                    $myDiscount->LastUpdated = $discount['LastUpdated'];

                    if (!$myDiscount->save())
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
        $discount = Discount::find($id);
        if (!empty($discount)) {
            if ($discount->delete())
                return $this->beautifyReturn(200, ['Extra' => 'Deleted']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }
}
