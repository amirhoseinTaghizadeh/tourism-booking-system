<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class NotifyAdminOfBooking implements ShouldQueue
{
    public function handle(BookingCreated $event)
    {
        $booking = $event->booking;

        Mail::to('admin@example.com')->send(new \App\Mail\AdminBookingNotification($booking));
    }
}
