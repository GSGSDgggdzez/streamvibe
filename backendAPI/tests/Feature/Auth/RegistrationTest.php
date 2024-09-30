<?php

/**
 * Test case: Verify that new users can successfully register
 *
 * This test performs the following steps:
 * 1. Sends a POST request to the '/register' endpoint with user registration data
 * 2. Checks if the user is authenticated after registration
 * 3. Verifies that the response status is 201 (Created)
 */
test('new users can register', function () {
    // Send a POST request to the registration endpoint with user data
    $response = $this->post('/register', [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    // Assert that the user is authenticated after registration
    $this->assertAuthenticated();

    // Assert that the response status is 201 (Created)
    $response->assertCreated();
});
