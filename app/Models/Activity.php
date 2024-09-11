<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'location', 'price', 'available_slots', 'image', 'start_date'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (isset($filters['location'])) {
            $query->where('location', 'like', '%' . $filters['location'] . '%');
        }

        if (isset($filters['price'])) {
            $query->where('price', '<=', $filters['price']);
        }

        if (isset($filters['available_slots'])) {
            $query->where('available_slots', '>=', $filters['available_slots']);
        }

        return $query;
    }
}
