<?php

namespace App\Http\Controllers\Hotels;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Apartment\Apartment;
use App\Models\Hotel\Hotel;
use DateTime;
use App\Models\Booking\Booking; 
use Illuminate\Support\Facades\Auth;

class HotelsController extends Controller
{
    //

    public function rooms($id){
        $getrooms = Apartment::select()->orderBy('id','desc')->take(6)->where('hotel_id', $id)->get();

        return view('hotels.rooms', compact('getrooms'));
    }


    public function roomsDetails($id){
        $getroom = Apartment::find($id);

        return view('hotels.roomdetails', compact('getroom'));
    }

    public function roomsBooking(Request $request,$id){


        $room = Apartment::find($id);
        $hotel = Hotel::find($id);
        if (Auth::check()) {
            // Get the authenticated user's ID
            $userId = Auth::user()->id;
        } else {
            // Handle case where the user is not authenticated
            return redirect()->route('login')->with('error', 'You need to be logged in to book a room.');
        }

        if (!$hotel) {
            // Handle case where the hotel is not found
            return abort(404);
        }


        if(strval(date("n/j/Y")) < strval($request->check_in) AND strval(date("n/j/Y")) < strval($request->check_out)) {

            if( $request->check_in <  $request->check_out )
        {
            $date1 = new DateTime($request->check_in);
            $date2 = new DateTime($request->check_out);

            $interval = $date1->diff($date2);
            $days= $interval->format('%a');

           //logic 
           $bookRooms = Booking::create(
            [
                "name"=> $request->name,
                "email"=> $request->email,
                "phone_number"=> $request->phone_number,
                "check_in"=> $request->check_in,
                "check_out"=> $request->check_out,
                "days"=> $days,
                "price"=> 0,
                "user_id"=> Auth::user()->id,
                "room_name"=> $room->name,
                "hotel_name"=> $hotel->name,
            ]
            );

            echo " Booked successfully";
        } else {
        echo "check out date should be greater";
        }
} else{ 
        echo"choose future dates, invalid date";
     }
    }
}


