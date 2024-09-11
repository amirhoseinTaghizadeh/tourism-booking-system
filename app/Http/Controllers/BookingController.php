<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Activity;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('activity')->get();
        return BookingResource::collection($bookings);
    }

    public function store(StoreBookingRequest $request)
    {
        DB::transaction(function () use ($request) {
            $activity = Activity::findOrFail($request->activity_id);

            if ($activity->available_slots < $request->slots_booked) {
                abort(400, 'Not enough slots available.');
            }

            $activity->decrement('available_slots', $request->slots_booked);

            $booking = Booking::create([
                'activity_id' => $request->activity_id,
                'user_name' => $request->user_name,
                'user_email' => $request->user_email,
                'slots_booked' => $request->slots_booked,
                'status' => 'pending',
            ]);

            event(new \App\Events\BookingCreated($booking));
        });

        return response()->json(['message' => 'Booking created successfully'], 201);
    }

    public function show(Booking $booking)
    {
        return new BookingResource($booking->load('activity'));
    }

    public function update(StoreBookingRequest $request, Booking $booking)
    {
        $validated = $request->validated();

        $slotsDifference = $validated['slots_booked'] - $booking->slots_booked;

        if ($slotsDifference > 0) {
            if ($booking->activity->available_slots < $slotsDifference) {
                return response()->json(['message' => 'Not enough available slots'], 400);
            }
            $booking->activity->decrement('available_slots', $slotsDifference);
        } elseif ($slotsDifference < 0) {
            $booking->activity->increment('available_slots', abs($slotsDifference));
        }

        $booking->update($validated);

        return response()->json(['message' => 'Booking updated successfully', 'booking' => $booking], 200);
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return response()->noContent();
    }
}
