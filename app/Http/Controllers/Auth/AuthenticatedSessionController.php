<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate(); // Attempt authentication

        // Check authentication status in multiple guards
        $guards = ['web', 'admin'];
        $user = null;

        foreach ($guards as $guard) {
            $currentGuard = Auth::guard($guard);
            if ($currentGuard->check()) {
                $user = $currentGuard->user(); // Get authenticated user
                break;
            }
        }

        $request->session()->regenerate(); // Regenerate session ID

        if ($user !== null) {
            // Return user data and token upon successful authentication
            return response()->json([
                'user' => $user,
                'token' => $user->createToken('api', [$user->getRoleAttribute()])->plainTextToken,
            ]);
        } else {
            // Handle case where authentication fails
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        Auth::guard('web')->logout(); // Logout from web guard

        $request->session()->invalidate(); // Invalidate session

        $request->session()->regenerateToken(); // Regenerate CSRF token

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
