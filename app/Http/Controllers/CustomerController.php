<?php

namespace App\Http\Controllers;

use App\Customer;
use App\RailCard;
use App\Traits\AddressTrait;
use App\Traits\ReturnTrait;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    use AddressTrait;
    use ReturnTrait;

    protected $className = 'Customer';

    public function index()
    {
        $customer = Customer::all();

        return response()->json($customer);
    }

    public function byID($id)
    {
        $customer = Customer::find($id);
        if (!empty($customer))
            return response()->json($customer);

        return $this->beautifyReturn(404);
    }

    /**
     * Created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        if ($request->CustommerID
            && $request->AddressID
            && $request->FirstName
            && $request->LastName
            && $request->BirthDate
            && $request->Email
            && $request->LastUpdated
        ) {
            $customer = new Customer();

            $railcard = new RailCard();
            $railcard->RailcardID = $request->CustomerID;
            $railcard->LastUpdated = $request->LastUpdated;

            if (!$railcard->save())
                return $this->beautifyReturnMessage('400', 'Railcard could not be saved');

            $customer->CustommerID = $request->CustommerID;
            $customer->RailCardID = $railcard->CardID;
            $customer->AddressID = $request->AddressID;
            $customer->FirstName = $request->FirstName;
            $customer->LastName = $request->LastName;
            $customer->BirthDate = $request->BirthDate;
            $customer->Email = $request->Email;
            $customer->LastUpdated = $request->LastUpdated;

            if ($customer->save())
                return $this->beautifyReturn(200, ['Extra' => 'Created', 'CustomerID' => $customer->CustomerID]);

            return $this->beautifyReturn(406);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $customer = Customer::find($id);
        if (!empty($customer)) {
            if ($request->AddressID)
                $customer->AddressID = $request->AddressID;
            if ($request->FirstName)
                $customer->FirstName = $request->FirstName;
            if ($request->LastName)
                $customer->LastName = $request->LastName;
            if ($request->BirthDate)
                $customer->BirthDate = $request->BirthDate;
            if ($request->Email)
                $customer->Email = $request->Email;
            if ($request->LastUpdated)
                $customer->LastUpdated = $request->LastUpdated;
            else
                $customer->LastUpdated = time();


            if ($customer->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $customer = Customer::find($id);
        if (!empty($customer)) {
            if ($customer->delete())
                return $this->beautifyReturn(200, ['Extra' => 'Deleted']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }
}
