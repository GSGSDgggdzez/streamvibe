<?php

use App\Models\User;

/**
 * Test case: Users can authenticate using the login screen
 *
 * This test verifies that users can successfully log in using valid credentials.
 * It creates a test user, attempts to log in, and checks for successful authentication.
 */
test('users can authenticate using the login screen', function () {
    // Create a test user using the User factory
    $user = User::factory()->create();

    // Attempt to log in by sending a POST request to the login endpoint
    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password', // Note: 'password' is the default password set by the factory
    ]);

    // Assert that the user is authenticated after the login attempt
    $this->assertAuthenticated();
    // Assert that the response has a 201 (Created) status code
    $response->assertCreated();
});

/**
 * Test case: Users cannot authenticate with an invalid password
 *
 * This test ensures that users cannot log in when providing an incorrect password.
 * It creates a test user and attempts to log in with a wrong password.
 */
test('users can not authenticate with invalid password', function () {
    // Create a test user using the User factory
    $user = User::factory()->create();

    // Attempt to log in with an incorrect password
    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password', // Intentionally using an incorrect password
    ]);

    // Assert that the user is still a guest (not authenticated) after the failed login attempt
    $this->assertGuest();
});

/**
 * Test case: Users can logout
 *
 * This test verifies that authenticated users can successfully log out.
 * It creates a test user, logs them in, and then attempts to log out.
 */
test('users can logout', function () {
    // Create a test user using the User factory
    $user = User::factory()->create();

    // Send a POST request to the logout endpoint while acting as the authenticated user
    $response = $this->actingAs($user)->post('/logout');

    // Assert that the user is now a guest (not authenticated) after logging out
    $this->assertGuest();
    // Assert that the response has a 201 (Created) status code
    $response->assertCreated();
});
