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

    public function roomsBooking(Request $request, $id)
{
    $room = Apartment::find($id);
    $hotel = Hotel::find($id);

    if (!$hotel) {
        return abort(404, 'Hotel not found.');
    }

    if (!$room) {
        return abort(404, 'Room not found.');
    }

    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'You need to be logged in to book a room.');
    }

    $checkIn = new DateTime($request->check_in);
    $checkOut = new DateTime($request->check_out);
    $currentDate = new DateTime();

    if ($checkIn <= $currentDate || $checkOut <= $currentDate) {
        return redirect()->back()->withErrors(['error' => 'Choose future dates.']);
    }

    if ($checkIn >= $checkOut) {
        return redirect()->back()->withErrors(['error' => 'Check-out date should be greater than check-in date.']);
    }

    $interval = $checkIn->diff($checkOut);
    $days = $interval->format('%a');

    // Create booking record
    $bookedRoom = Booking::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone_number' => $request->phone_number,
        'check_in' => $request->check_in,
        'check_out' => $request->check_out,
        'days' => $days,
        'price' => 0, // Update with actual price logic
        'user_id' => Auth::user()->id,
        'room_name' => $room->name,
        'hotel_name' => $hotel->name,
    ]);

    if ($bookedRoom) {
        return redirect()->route('success')->with('success', 'Room booked successfully.');
    } else {
        return redirect()->back()->withErrors(['error' => 'Failed to book room.']);
    }
}
}