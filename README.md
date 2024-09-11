# Tourism Activity Booking System

## Overview
This project is a Laravel-based Tourism Activity Booking System where users can browse and book various activities. The system handles booking confirmations, notifications, and reminders using Laravel's queue and event/listener system.

## Features

### Activity Management
- Create, read, update, and delete activities.
- Upload images for activities.
- Manage activity details including name, description, location, price, and available slots.

### Booking Management
- Users can book activities by specifying the number of slots.
- Automatically reduce available slots upon booking.
- Booking status management (`pending`, `confirmed`, `cancelled`).
- Queue-based email confirmations for users after booking.
- Admin notifications for each booking made.
- Users receive reminder emails 24 hours before the activity starts.

### Search and Filtering
- Search activities by name, location, and price.
- Filter activities based on availability.

### Notifications
- Queue-based email notifications for booking confirmation and reminders.
- Admin notifications when a new booking is made.




## Implementation Details



### Routes
- Resourceful routes for activities and bookings are defined in `routes/api.php`.

### Validation
- Form requests are used to validate incoming data for creating and updating activities and bookings.

### Queues and Event/Listener System
- Queue-based system for sending email notifications.
- **Events:**
  - `BookingCreated`
- **Listeners:**
  - `SendBookingConfirmationEmail`
  - `NotifyAdminOfBooking`

### Task Scheduling
- Laravel’s task scheduler is used to send reminder emails 24 hours before a booked activity starts.

