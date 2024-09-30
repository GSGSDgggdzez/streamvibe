<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TokenVerificationController extends Controller
{
    //
    public function verify(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Check if token needs refresh (e.g., if it's close to expiration)
        if ($this->shouldRefreshToken($user->currentAccessToken())) {
            $newToken = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['message' => 'Token refreshed'])
                ->cookie('auth_token', $newToken, 60 * 24 * 30, null, null, true, true);
        }

        return response()->json(['message' => 'Token is valid']);
    }

    private function shouldRefreshToken($token)
    {
        // Check if the token is close to expiration (e.g., within 24 hours)
        $expirationTime = $token->expires_at;
        $now = now();
        $timeUntilExpiration = $expirationTime->diffInHours($now);

        // Refresh if the token expires within 24 hours
        return $timeUntilExpiration <= 24;
    }
}
