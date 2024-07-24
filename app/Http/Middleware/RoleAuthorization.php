<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Models\Product;

class RoleAuthorization
{
    public function handle(Request $request, Closure $next, $accessRight, $resourceType = null)
    {
        try {
            $token = JWTAuth::parseToken();
            $user = $token->authenticate();
        } catch (TokenExpiredException $e) {
            return $this->unauthorized('Your token has expired. Please, login again.');
        } catch (TokenInvalidException $e) {
            return $this->unauthorized('Your token is invalid. Please, login again.');
        } catch (JWTException $e) {
            return $this->unauthorized('Please, attach a Bearer Token to your request');
        }

        if ($user) {
            $role = $user->role;
            $roleAccessRights = json_decode($role->access_rights, true);

            if (in_array($accessRight, $roleAccessRights)) {
                if ($resourceType) {
                    $resourceId = $request->route('id');
                    if (!$this->checkOwnership($user, $resourceType, $resourceId)) {
                        return $this->unauthorized('You do not own this resource');
                    }
                }
                return $next($request);
            }
        }

        return $this->unauthorized();
    }

    private function checkOwnership($user, $resourceType, $resourceId)
    {
        switch ($resourceType) {
            case 'product':
                $resource = Product::find($resourceId);
                break;
            default:
                return false;
        }

        if ($resource && $resource->created_by === $user->id) {
            return true;
        }

        return false;
    }

    private function unauthorized($message = null)
    {
        return response()->json([
            'message' => $message ? $message : 'You are unauthorized to access this resource',
            'success' => false
        ], 401);
    }
}
