<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class RoleAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $accessRight
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $accessRight)
    {
        try {
            // Access token from the request
            $token = JWTAuth::parseToken();
            // Try authenticating user
            $user = $token->authenticate();
        } catch (TokenExpiredException $e) {
            // Thrown if token has expired
            return $this->unauthorized('Your token has expired. Please, login again.');
        } catch (TokenInvalidException $e) {
            // Thrown if token invalid
            return $this->unauthorized('Your token is invalid. Please, login again.');
        } catch (JWTException $e) {
            // Thrown if token was not found in the request
            return $this->unauthorized('Please, attach a Bearer Token to your request');
        }

        if ($user) {
            // Check user's role for access rights
            $role = $user->role;
            $roleAccessRights = json_decode($role->access_rights, true);

            if (in_array($accessRight, $roleAccessRights)) {
                return $next($request);
            }
        }

        return $this->unauthorized();
    }

    private function unauthorized($message = null)
    {
        return response()->json([
            'message' => $message ? $message : 'You are unauthorized to access this resource',
            'success' => false
        ], 401);
    }
}
