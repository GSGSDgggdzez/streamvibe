<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

// Test case to verify that a password reset link can be requested
test('reset password link can be requested', function () {
    // Fake the notification so we can assert it was sent
    Notification::fake();

    // Create a user using the factory
    $user = User::factory()->create();

    // Send a POST request to the forgot-password endpoint with the user's email
    $this->post('/forgot-password', ['email' => $user->email]);

    // Assert that a ResetPassword notification was sent to the user
    Notification::assertSentTo($user, ResetPassword::class);
});

// Test case to verify that a password can be reset with a valid token
test('password can be reset with valid token', function () {
    // Fake the notification so we can assert it was sent and access its content
    Notification::fake();

    // Create a user using the factory
    $user = User::factory()->create();

    // Send a POST request to the forgot-password endpoint with the user's email
    $this->post('/forgot-password', ['email' => $user->email]);

    // Assert that a ResetPassword notification was sent to the user and perform additional checks
    Notification::assertSentTo($user, ResetPassword::class, function (object $notification) use ($user) {
        // Send a POST request to the reset-password endpoint with the token from the notification
        $response = $this->post('/reset-password', [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // Assert that the response has no errors and returns a 200 status code
        $response
            ->assertSessionHasNoErrors()
            ->assertStatus(200);

        return true;
    });
});
