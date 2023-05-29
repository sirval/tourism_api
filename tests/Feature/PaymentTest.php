<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use App\Traits\Utils;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class PaymentTest extends TestCase
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

    public function testPaystackPaymentAndCallback()
    {
        $user = $this->createUser(2);
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
    

        $booking = Booking::create($bookingAttributes);
      
        $tx_ref = Str::uuid();

        // Make the payment request
        $response = $this->actingAs($user) 
            ->postJson('/api/user/booking/make-payment', [
                'booking_id' => $booking->id,
                'amount' => (int) 1000 * 100, 
                'reference' => $tx_ref, 
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'headers' => [],
                'original' =>[
                    'response',
                    'status',
                    'message',
                   'data' => [], //$response['original']['data'],
                ],
                'exception',
            ]);
       
        // Simulate the callback from Paystack
        $callbackResponse = $this->actingAs($user) 
            ->get('/api/user/booking/payment/callback', [
                'reference' => $tx_ref, 
                'status' => 'success', 
            ]);

        // Assert the callback response
        $callbackResponse->assertStatus(200);
    }

}
