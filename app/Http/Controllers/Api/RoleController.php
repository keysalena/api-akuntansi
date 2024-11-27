<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API Documentation",
 *     description="API documentation for managing Role resources"
 * )
 *
 * @OA\Tag(
 *     name="Role",
 *     description="API Endpoints for Role"
 * )
 */

/**
 * @OA\Schema(
 *     schema="Role",
 *     @OA\Property(property="id_role", type="string", example="akun_123"),
 *     @OA\Property(property="role", type="string", example="John Doe")
 * )
 */

class RoleController extends Controller
{
    /**
     * Display a listing of Roles.
     *
     * @OA\Get(
     *     path="/api/role",
     *     tags={"Role"},
     *     summary="Get list of Roles",
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Role"))
     *     )
     * )
     */
    public function index()
    {
        $role = Role::latest()->paginate(5);
        return new RoleResource(true, 'List Data Roles', $role);
    }

     /**
     * Store a new Role.
     *
     * @OA\Post(
     *     path="/api/role",
     *     tags={"Role"},
     *     summary="Create a new Role",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="role", type="string", example="John Doe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Role created",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role = Role::create([
            'role'   => $request->role,
        ]);

        return new RoleResource(true, 'Data Role Berhasil Ditambahkan!', $role);
    }

    /**
     * Display an Role by ID.
     *
     * @OA\Get(
     *     path="/api/role/{id}",
     *     tags={"Role"},
     *     summary="Get an Role by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
     *     ),
     *     @OA\Response(response=404, description="Role not found")
     * )
     */
    public function show($id)
    {
        $role = Role::find($id);
        return new RoleResource(true, 'Detail Data Role!', $role);
    }

     /**
     * Update an existing Role.
     *
     * @OA\Put(
     *     path="/api/role/{id}",
     *     tags={"Role"},
     *     summary="Update an Role by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="role", type="string", example="Jane Doe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role updated",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
     *     ),
     *     @OA\Response(response=404, description="Role not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'role'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $role = Role::find($id);

        $role->update([
            'role'   => $request->role,
        ]);

        return new RoleResource(true, 'Data Role Berhasil Diubah!', $role);
    }

    /**
     * Delete an Role.
     *
     * @OA\Delete(
     *     path="/api/role/{id}",
     *     tags={"Role"},
     *     summary="Delete an Role by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=204, description="Role deleted"),
     *     @OA\Response(response=404, description="Role not found")
     * )
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        $role->delete();
        return new RoleResource(true, 'Data Role Berhasil Dihapus!', null);
    }
}
