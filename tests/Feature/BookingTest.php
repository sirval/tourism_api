<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookingTest extends TestCase
{
    // use DatabaseTransactions;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Run migrations for the test database
        $this->artisan('migrate');
        // Call the database seeder
        $this->seed();
    }

    protected function createUser($role_id = 2)
    {
        return User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role_id,
        ]);
    }

    public function testUserCanCreateBooking()
    {
        $user = $this->createUser(2);
        $this->actingAs($user);

        $bookingAttributes = [
            'user_id' => $user->id,
            'travel_option_id' => 1,
            'from' => 'Lagos',
            'to' => 'Owerri',
            'phone' => '123456789',
            'booking_email' => $user->email,
            'departure_date' => '2023-06-10',
            'arrival_date' => '2023-06-12',
            'num_guest' => 2,
            'amount' => 10000 ,
        ];
    
        // Send a POST request to create a booking
        $response = $this->postJson('/api/user/booking', $bookingAttributes);
    
        // Assert the response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'headers' => [],
                'original' =>[
                    'response',
                    'status',
                    'message',
                    'data' => []
                ],
                'exception'
        
            ]);
        
    }

    public function testAmountIsConvertedToSmallestUnitAndReconvertedToUserActualAmount()
    {
        $user = $this->createUser(2);
        $this->actingAs($user);

        $bookingAttributes = [
            'user_id' => $user->id,
            'travel_option_id' => 1,
            'from' => 'Lagos',
            'to' => 'Owerri',
            'phone' => '123456789',
            'booking_email' => $user->email,
            'departure_date' => '2023-06-10',
            'arrival_date' => '2023-06-12',
            'num_guest' => 2,
            'amount' => 10000,
        ];
    

        Booking::create($bookingAttributes);

        // Retrieve the booking from the database
        $bookingFromDatabase = Booking::first();

        $this->assertEquals($bookingAttributes['amount'], $bookingFromDatabase->amount);
       
    }
    

    public function testBookingDeletion()
    {
        $user = $this->createUser(2);
        $this->actingAs($user);
        $booking = Booking::create([
            'user_id' => 1,
            'travel_option_id' => 1,
            'from' => 'Lagos',
            'to' => 'Owerri',
            'phone' => '123456789',
            'booking_email' => 'test@example.com',
            'departure_date' => '2023-06-01',
            'arrival_date' => '2023-06-03',
            'num_guest' => 2,
            'amount' => 100000,
        ]);

        // dd($booking->id);
        // Delete the booking
        $response = $this->delete('/api/user/booking/' . $booking->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }

    public function testBookingUpdate()
    {
        $user = $this->createUser(2);
        $this->actingAs($user);

        $booking = Booking::create([
            'user_id' => 1,
            'travel_option_id' => 1,
            'from' => 'Lagos',
            'to' => 'Owerri',
            'phone' => '123456789',
            'booking_email' => 'test@example.com',
            'departure_date' => '2023-06-01',
            'arrival_date' => '2023-06-03',
            'num_guest' => 2,
            'amount' => 100000,
        ]);

        // Update the booking
        $updatedData = [
            'user_id' => 1,
            'travel_option_id' => 1,
            'from' => 'Abuja',
            'to' => 'Kano',
            'phone' => '98765432109',
            'booking_email' => 'test@example.com',
            'departure_date' => '2023-06-01',
            'arrival_date' => '2023-06-03',
            'num_guest' => 3,
            'amount' => 1000000,
            '_method' => 'patch',
        ];

       $response = $this->postJson('/api/user/booking/' . $booking->id,
            $updatedData
        );
        // dd($response);

        $response->assertStatus(200);

        $updatedBooking = Booking::find($booking->id);

        // Assert that the booking attributes were updated correctly
        $this->assertEquals($updatedData['from'], $updatedBooking->from);
        $this->assertEquals($updatedData['to'], $updatedBooking->to);
        $this->assertEquals($updatedData['num_guest'], $updatedBooking->num_guest);
    }

    public function testUserCanCancelBooking()
    {
        $user = $this->createUser(2);
        $this->actingAs($user);

        $booking = Booking::create([
            'user_id' => 1,
            'travel_option_id' => 1,
            'from' => 'Lagos',
            'to' => 'Owerri',
            'phone' => '123456789',
            'booking_email' => 'test@example.com',
            'departure_date' => '2023-06-01',
            'arrival_date' => '2023-06-03',
            'num_guest' => 2,
            'amount' => 100000,
        ]);

       $response = $this->postJson('/api/user/booking/cancel/' . $booking->id
        );
        // dd($response);

        $response->assertStatus(200);

    }

    public function testUserCannotCancelBookingAfterPayment()
    {
        $user = $this->createUser(2);
        $this->actingAs($user);

        $booking = Booking::create([
            'user_id' => 1,
            'travel_option_id' => 1,
            'from' => 'Lagos',
            'to' => 'Owerri',
            'phone' => '123456789',
            'booking_email' => 'test@example.com',
            'departure_date' => '2023-06-01',
            'arrival_date' => '2023-06-03',
            'num_guest' => 2,
            'amount' => 100000,
            'payment_status' => 1,
        ]);

       $response = $this->postJson('/api/user/booking/cancel/' . $booking->id
        );
        // dd($response);

        $response->assertStatus(200);

    }

    public function testOnlyAdminCanCancelBookingAfterPayment()
    {
        $user = $this->createUser(1);
        $this->actingAs($user);

        $booking = Booking::create([
            'user_id' => 1,
            'travel_option_id' => 1,
            'from' => 'Lagos',
            'to' => 'Owerri',
            'phone' => '123456789',
            'booking_email' => 'test@example.com',
            'departure_date' => '2023-06-01',
            'arrival_date' => '2023-06-03',
            'num_guest' => 2,
            'amount' => 100000,
            'payment_status' => 1,
        ]);

       $response = $this->postJson('/api/admin/booking/cancel/' . $booking->id
        );

        $response->assertStatus(200);

    }

}
