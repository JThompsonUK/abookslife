<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;

class UserControllerTest extends TestCase
{
    public function testStoreMethodCreatesUserAndSendsWelcomeEmail()
    {
        // Mock the Mail facade to fake email sending
        Mail::fake();

        // Define user data
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ];

        // Make a POST request to the store endpoint with user data
        $response = $this->post(route('users.store'), $userData);

        // Assert that the response is a redirect (assuming successful registration)
        $response->assertRedirect();

        // Assert that a new user with the provided data is created
        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);

        // Assert that a welcome email is sent to the user
        Mail::assertSent(WelcomeEmail::class, function ($mail) use ($userData) {
            return $mail->hasTo($userData['email']) && $mail->userName === $userData['name'];
        });
    }
}
