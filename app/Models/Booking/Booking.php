<?php

namespace App\Models\Booking; // Adjust the namespace

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    
    // Specify the table name
    protected $table = "booking";

    // Specify the fillable fields
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'check_in',
        'check_out',
        'days',
        'price',
        'user_id',
        'room_name',
        'hotel_name',
        'status', // Remove the extra space
    ];

    // Indicate whether timestamps are used
    public $timestamps = true;
}
