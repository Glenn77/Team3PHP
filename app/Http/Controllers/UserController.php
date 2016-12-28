<?php

namespace App\Http\Controllers;


use App\User;
use App\Traits\AddressTrait;
use App\Traits\ExceptionTrait;
use App\Traits\ReturnTrait;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    use AddressTrait;
    use ExceptionTrait;
    use ReturnTrait;

    protected $className = 'Staff Member';

    public function login(Request $request)
    {

        if (Auth::once(['username' => $request->username, 'password' => $request->password]))
        {
            $result = [
                'StatusCode' => 200,
                'Api_token' => Auth::user()->Api_token,
                'StaffID' => Auth::user()->StaffID,
                'Rights' => Auth::user()->Rights,
            ];

            return response()->json($result);
        }

        return $this->beautifyReturnMessage(400, 'wrong username or password');
    }

    public function index()
    {
        $user = User::all();

        return response()->json($user);
    }

    public function byID($id)
    {
        $user = User::find($id);
        if (!empty($user))
            return response()->json($user);

        return $this->beautifyReturn(404);
    }

    public function create(Request $request)
    {
        if ($request->StaffID
            && $request->AddressID
            && $request->StationID
            && $request->FirstName
            && $request->LastName
            && $request->UserName
            && $request->Password
            && isset($request->Rights)
            && $request->BirthDate
            && $request->Email
            && $request->LastUpdated
        ) {
            $user = new User();
            $user->StaffID = $request->StaffID;
            $user->AddressID = $request->AddressID;
            $user->StationID = $request->StationID;
            $user->FirstName = $request->FirstName;
            $user->LastName = $request->LastName;
            $user->UserName = $request->UserName;
            $user->Password = Hash::make($request->Password);
            $user->Rights = $request->Rights;
            $user->BirthDate = $request->BirthDate;
            $user->Email = $request->Email;
            $user->Api_token = Hash::make(uniqid($user->UserName, true));
            $user->LastUpdated = $request->LastUpdated;

            try {
                if ($user->save())
                    return $this->beautifyReturn(200, ['Extra' => 'Created', 'Staff MemberID' => $user->StaffID]);

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

        $user = User::find($id);
        if (!empty($user)) {
            if ($request->AddressID)
                $user->AddressID = $request->AddressID;
            if ($request->StationID)
                $user->StationID = $request->StationID;
            if ($request->FirstName)
                $user->FirstName = $request->FirstName;
            if ($request->LastName)
                $user->LastName = $request->LastName;
            if ($request->UserName)
                $user->UserName = $request->UserName;
            if ($request->Password)
                $user->Password = Hash::make($request->Password);
            if (isset($request->Rights))
                $user->Rights = $request->Rights;
            if ($request->BirthDate)
                $user->BirthDate = $request->BirthDate;
            if ($request->Email)
                $user->Email = $request->Email;
            if ($request->LastUpdated)
                $user->LastUpdated = $request->LastUpdated;
            else
                $user->LastUpdated = time();


            if ($user->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);

    }

    public function massUpdateStatus()
    {
        $status = DB::select('SELECT COUNT(DISTINCT StaffID) as Count, MAX(LastUpdated) as LastUpdated FROM Staff');
        return response()->json($status[0]);
    }

    public function massUpdate(Request $request)
    {

        if (!empty($request->StaffList)) {

            $staffList = $request->StaffList;

            try
            {
                foreach ($staffList as $staff)
                {
                    $myStaff = User::find($staff['StaffID']);

                    if (empty($myStaff))
                        $myStaff = New User();

                    $myStaff->StaffID = $staff['StaffID'];
                    $myStaff->AddressID = $staff['AddressID'];
                    $myStaff->StationID = $staff['StationID'];
                    $myStaff->FirstName = $staff['FirstName'];
                    $myStaff->LastName = $staff['LastName'];
                    $myStaff->UserName = $staff['UserName'];
                    $myStaff->Password = $staff['Password'];
                    $myStaff->Rights = $staff['Rights'];
                    $myStaff->BirthDate = $staff['BirthDate'];
                    $myStaff->Email = $staff['Email'];
                    $myStaff->Api_token = $staff['Api_token'];
                    $myStaff->LastUpdated = $staff['LastUpdated'];

                    if (!$myStaff->save())
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
        $user = User::find($id);
        if (!empty($user)) {
            if ($user->delete())
                return $this->beautifyReturn(200, ['Extra' => 'Deleted']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }


    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'UserName';
    }
}
