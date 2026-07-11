<?php

namespace App\Http\Controllers;

use App\Models\Showtime;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    //

    public function myTickets(Request $request)
    {
        $tickets = Ticket::where("user_id", $request->user()->id)
            ->with(["showtime.movie"])
            ->get();

        return response()->json([
            "message" => "Tickets Getted Successefully.",
            "tickets" => $tickets
        ], 200);
    }

    public function validateTicket(string $code)
    {

        $ticket = Ticket::where("ticket_code", $code)->first();

        if (!$ticket) {
            return response()->json([
                "message" => "Sorry, Invalid Ticket or completely non-existent"
            ], 400);
        }

        if ($ticket->is_used) {
            return response()->json([
                "message" => "Notation : Ticket Already Used!"
            ], 400);
        }

        $ticket->update(['is_used' => true]);

        return response()->json([
            "message" => "Ticket valid! Entry confirmed successfully. 🎉"
        ], 200);
    }

    public function buyTicket(Request $request)
    {
        // validate showtime ID should (exists and and not empty)
        $request->validate([
            'showtime_id' => 'required|exists:showtimes,id'
        ]);

        $user = $request->user();
        $showtime = Showtime::findOrFail($request->showtime_id);

        // check if user has reach the limit of Five Tickets
        if ($this->hasReachedTicketLimits($user, $showtime)) {
            return response()->json([
                'message' => "Sorry, You cannot buy more than 5 Tickets"
            ], 400);
        }

        // check if room is full
        if ($this->isRoomFull($showtime)) {
            return response()->json([
                'message' => "Sorry, Tickets Sold out, all places are taken."
            ], 400);
        }

        // creation of Ticket
        $ticket = $this->createNewTicket($user, $showtime);

        return response()->json([
            'message' => "Ticket successfully booked!",
            'ticket' => $ticket
        ], 201);
    }

    private function hasReachedTicketLimits(User $user, Showtime $showtime)
    {

        $userTicketCount = Ticket::where('showtime_id', $showtime->id)
            ->where('user_id', $user->id)
            ->count();

        return $userTicketCount >= 5;
    }

    private function isRoomFull(Showtime $showtime)
    {
        $totalSoldTickets = Ticket::where('showtime_id', $showtime->id)
            ->count();
        return ($totalSoldTickets + 1) > $showtime->capacity;
    }

    private function createNewTicket(User $user, Showtime  $showtime)
    {
        return Ticket::create([
            'showtime_id' => $showtime->id,
            'user_id' => $user->id,
            'ticket_code' => (string) Str::uuid(), // generate UUID Unique Code for scaning 
            'is_used' => false
        ]);
    }
}
