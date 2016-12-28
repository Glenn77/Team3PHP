<?php
/**
 * Created by PhpStorm.
 * User: Ludovic
 * Date: 09-11-16
 * Time: 18:02
 */

namespace App\Traits;

use App\Address;
use GuzzleHttp\Message\Response;
use Illuminate\Http\Request;

trait AddressTrait
{
    public function createNewAddress(Request $request)
    {


        if ($request->AddressID
            && $request->Street
            && $request->Number
            && $request->City
            && $request->ZipCode
            && isset($request->Coordinates)
            && $request->LastUpdated
        ) {
            $address = new Address();
            $address->AddressID = $request->AddressID;
            $address->Street = $request->Street;
            $address->Number = $request->Number;
            $address->City = $request->City;
            $address->ZipCode = $request->ZipCode;
            $address->Coordinates = $request->Coordinates;
            $address->LastUpdated = $request->LastUpdated;

            if ($address->save())
                return $address->AddressID;

            return $this->beautifyReturnMessage(406, 'Address Not Acceptable');
        }
        return $this->beautifyReturnMessage(400, 'Address Bad Request');
    }
}