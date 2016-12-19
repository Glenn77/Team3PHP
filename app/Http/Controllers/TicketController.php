<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Traits\ReturnTrait;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    use ReturnTrait;

    protected $className = 'Ticket';

    public function index()
    {
        $ticket = Ticket::all();
        return response()->json($ticket);
    }

    public function byID($id)
    {
        $ticket = Ticket::find($id);
        if (!empty($ticket))
            return response()->json($ticket);

        return $this->beautifyReturn(404);
    }

    public function create(Request $request)
    {
        if ( $request->TicketID
            && $request->RouteID
            && $request->TypeTicketID
            && $request->Date
            && $request->ValidFrom
            && $request->ValidUntil
            && $request->LastUpdated
        ) {
            $ticket = new Ticket();
            $ticket->TicketID = $request->TicketID;
            $ticket->RouteID = $request->RouteID;
            $ticket->TypeTicketID = $request->TypeTicketID;
            $ticket->Date = $request->Date;
            $ticket->ValidFrom = $request->ValidFrom;
            $ticket->ValidUntil = $request->ValidUntil;
            $ticket->LastUpdated = $request->LastUpdated;

            if ($ticket->save())
                return $this->beautifyReturn(200, ['Extra' => 'Created', 'TicketID' => $ticket->TicketID]);

            return $this->beautifyReturn(406);
        }
        return $this->beautifyReturn(400);
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::find($id);
        if (!empty($ticket)) {
            if ($request->RouteID)
                $ticket->RouteID = $request->RouteID;
            if ($request->TypeTicketID)
                $ticket->TypeTicketID = $request->TypeTicketID;
            if ($request->Date)
                $ticket->Date = $request->Date;
            if ($request->ValidFrom)
                $ticket->ValidFrom = $request->ValidFrom;
            if ($request->ValidUntil)
                $ticket->ValidUntil = $request->ValidUntil;
            if ($request->LastUpdated)
                $ticket->LastUpdated = $request->LastUpdated;
            else
                $ticket->LastUpdated = time();


            if ($ticket->save())
                return $this->beautifyReturn(200, ['Extra' => 'Updated']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }

    public function delete($id)
    {
        $ticket = Ticket::find($id);
        if (!empty($ticket)) {
            if ($ticket->delete())
                return $this->beautifyReturn(200, ['Extra' => 'Deleted']);
        } else {
            return $this->beautifyReturn(404);
        }
        return $this->beautifyReturn(400);
    }
}
