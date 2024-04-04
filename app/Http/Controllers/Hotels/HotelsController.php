<?php

namespace App\Http\Controllers\Hotels;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Apartment\Apartment;


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
}


