<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_bookings()
    {
        $activity = Activity::factory()->create();
        $booking = Booking::factory()->create(['activity_id' => $activity->id]);

        $response = $this->getJson('/api/bookings');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'activity_id' => $activity->id,
                'user_name' => $booking->user_name,
                'user_email' => $booking->user_email,
                'slots_booked' => $booking->slots_booked,
                'status' => $booking->status,
            ]);
    }

    /** @test */
    public function it_can_store_a_booking()
    {
        $activity = Activity::factory()->create(['available_slots' => 10]);

        $response = $this->postJson('/api/bookings', [
            'activity_id' => $activity->id,
            'user_name' => 'amir',
            'user_email' => 'amir@gmail.com',
            'slots_booked' => 2,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Booking created successfully',
            ]);

        $this->assertDatabaseHas('bookings', [
            'activity_id' => $activity->id,
            'user_name' => 'amir',
            'user_email' => 'amir@gmail.com',
            'slots_booked' => 2,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'available_slots' => 8,
        ]);
    }

    /** @test */
    public function it_can_show_a_booking()
    {
        $activity = Activity::factory()->create();
        $booking = Booking::factory()->create(['activity_id' => $activity->id]);

        $response = $this->getJson("/api/bookings/{$booking->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'activity_id' => $activity->id,
                'user_name' => $booking->user_name,
                'user_email' => $booking->user_email,
                'slots_booked' => $booking->slots_booked,
                'status' => $booking->status,
            ]);
    }

    /** @test */
    public function it_can_update_a_booking()
    {
        $activity = Activity::factory()->create(['available_slots' => 10]);
        $booking = Booking::factory()->create(['activity_id' => $activity->id, 'slots_booked' => 2]);

        $response = $this->putJson("/api/bookings/{$booking->id}", [
            'slots_booked' => 5,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Booking updated successfully',
            ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'slots_booked' => 5,
        ]);

        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'available_slots' => 5,
        ]);
    }

    /** @test */
    public function it_can_delete_a_booking()
    {
        $booking = Booking::factory()->create();

        $response = $this->deleteJson("/api/bookings/{$booking->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('bookings', [
            'id' => $booking->id,
        ]);
    }

    /** @test */
    public function it_returns_error_for_insufficient_slots_when_storing_booking()
    {
        $activity = Activity::factory()->create(['available_slots' => 1]);

        $response = $this->postJson('/api/bookings', [
            'activity_id' => $activity->id,
            'user_name' => 'Jane Doe',
            'user_email' => 'jane@example.com',
            'slots_booked' => 2,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Not enough slots available.',
            ]);
    }

}
