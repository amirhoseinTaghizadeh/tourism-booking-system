<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Booking;
use Carbon\Carbon;
use App\Mail\ActivityReminder;

class SendReminderEmails extends Command
{
    protected $signature = 'emails:send-reminders';
    protected $description = 'Send reminder emails to users 24 hours before their booked activity starts.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $bookings = Booking::where('start_date', '=', Carbon::now()->addDay()->toDateString())
            ->get();

        foreach ($bookings as $booking) {
            Mail::to($booking->user_email)->send(new ActivityReminder($booking));
        }
    }
}
