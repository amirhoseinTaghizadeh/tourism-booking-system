<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['activity_id', 'user_name', 'user_email', 'slots_booked', 'status'];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
