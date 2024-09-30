<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

// Test case to verify that an email can be verified
test('email can be verified', function () {
    // Create an unverified user using the factory
    $user = User::factory()->unverified()->create();

    // Fake the Event facade to track dispatched events
    Event::fake();

    // Generate a temporary signed URL for email verification
    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    // Simulate the user accessing the verification URL
    $response = $this->actingAs($user)->get($verificationUrl);

    // Assert that the Verified event was dispatched
    Event::assertDispatched(Verified::class);
    // Check if the user's email is now verified
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    // Assert that the user is redirected to the dashboard with a verified parameter
    $response->assertRedirect(config('app.frontend_url').'/dashboard?verified=1');
});

// Test case to verify that an email is not verified with an invalid hash
test('email is not verified with invalid hash', function () {
    // Create an unverified user using the factory
    $user = User::factory()->unverified()->create();

    // Generate a temporary signed URL with an incorrect email hash
    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1('wrong-email')]
    );

    // Simulate the user accessing the verification URL with the invalid hash
    $this->actingAs($user)->get($verificationUrl);

    // Check that the user's email remains unverified
    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});
