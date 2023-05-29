<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;
    // use RefreshDatabase;

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

    public function testUserCanAuthenticateWithValidCredentials()
    {
        $user = $this->createUser(2);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'original' => [
                    'response',
                    'status',
                    'message',
                    'data' => [
                        'access_token',
                        'token_type',
                        'expires_in',
                    ]
                ]
            ]);
        $this->assertAuthenticated();
        $this->assertAuthenticatedAs($user);
    }

    public function testAdminCanAccessProtectedRoute()
    {
        $admin = $this->createUser(1);
        $token = auth()->login($admin);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/admin/travel-options');

        $response->assertStatus(200);
    }

    public function testUserCannotAccessAdminOnlyRoute()
    {
        $user = $this->createUser(2);
        $token = auth()->login($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/admin/travel-options');

        $response->assertStatus(403)
            ->assertJson([
                'response' => false,
                'status' => 403,
                'message' => 'Access Forbidden',           
            ]);
    }

    public function testAuthenticatedUserCanLogout()
    {
        $user = $this->createUser(2);
        $token = auth()->login($user);
        $response = $this->postJson('/api/auth/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);
    
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
    
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
            'name' => 'TestToken',
        ]);
    }

    public function testUserCanRefreshToken()
    {
        $user = $this->createUser(2);
        $token = auth()->login($user);

        $response = $this->postJson('/api/auth/refresh', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

       
        $response->assertStatus(200)
            ->assertJsonStructure([
                'original' => [
                    'response',
                    'status',
                    'message',
                    'data' => [
                        'access_token',
                        'token_type',
                        'expires_in',
                    ]
                ]
            ]);

        $responseData = $response->json();
        // dd($responseData);
        $newToken = $responseData['original']['data']['access_token'];
        $this->assertNotEquals($token, $newToken);
    }
}
