<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Http\Resources\RoleResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    /**
     * Display a listing of the roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $roles = Role::all();
            return RoleResource::collection($roles);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve roles', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'role_name' => 'required|string|unique:roles',
                'access_rights' => 'required|string',
            ]);

            $role = Role::create($request->all());

            return new RoleResource($role);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create role', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $role = Role::findOrFail($id);
            return new RoleResource($role);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Role not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve role', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'role_name' => 'sometimes|required|string|unique:roles,role_name,' . $id,
                'access_rights' => 'sometimes|required|string',
            ]);

            $role = Role::findOrFail($id);
            $role->update($request->all());

            return new RoleResource($role);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Role not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update role', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();

            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Role not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete role', 'error' => $e->getMessage()], 500);
        }
    }
}
