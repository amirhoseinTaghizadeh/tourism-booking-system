<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

    class SendBookingConfirmationEmail implements ShouldQueue
    {
        public function handle(BookingCreated $event)
        {
            $booking = $event->booking;

            Mail::to($booking->user_email)->send(new \App\Mail\BookingConfirmation($booking));

            $booking->update(['status' => 'confirmed']);
        }
    }
