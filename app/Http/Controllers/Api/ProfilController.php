<?php

namespace App\Http\Controllers\Api;

use App\Models\Profil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfilResource;
use App\Models\Akun;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API Documentation",
 *     description="API documentation for managing Profil resources"
 * )
 *
 * @OA\Tag(
 *     name="Profil",
 *     description="API Endpoints for Profil"
 * )
 */

/**
 * @OA\Schema(
 *     schema="Profil",
 *     @OA\Property(property="id_profil", type="string", example="profil_123"),
 *     @OA\Property(property="id_role", type="string", example="role_123"),
 *     @OA\Property(property="kode", type="integer", example=101),
 *     @OA\Property(property="nama", type="string", example="John Doe")
 * )
 */
class ProfilController extends Controller
{
    /**
     * Display a listing of Profil.
     *
     * @OA\Get(
     *     path="/api/profil",
     *     tags={"Profil"},
     *     summary="Get list of Profil",
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Profil"))
     *     )
     * )
     */
    public function index()
    {
        $role = Profil::with(['role:id_role,role'])->orderBy('created_at', 'asc')->paginate(100);
        return new ProfilResource(true, 'List Data Profils', $role);
    }

    /**
     * Store a new Profil.
     *
     * @OA\Post(
     *     path="/api/profil",
     *     tags={"Profil"},
     *     summary="Create a new Profil",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama", type="string", example="John Doe"),
     *             @OA\Property(property="username", type="string", example="jhondoe"),
     *             @OA\Property(property="email", type="string", example="jhondoe@id"),
     *             @OA\Property(property="password", type="string", format="password", description="User password, minimum 8 characters"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Profil created",
     *         @OA\JsonContent(ref="#/components/schemas/Profil")
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'username' => 'required',
            'email' => 'required',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $profil = Profil::create([
            'id_role' => '6751826ea73ba',
            'nama' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return new ProfilResource(true, 'Data Profil Berhasil Ditambahkan!', $profil);
    }

    /**
     * Display a Profil by ID.
     *
     * @OA\Get(
     *     path="/api/profil/{id}",
     *     tags={"Profil"},
     *     summary="Get a Profil by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/Profil")
     *     ),
     *     @OA\Response(response=404, description="Profil not found")
     * )
     */
    public function show($id)
    {
        $profil = Profil::find($id);
        return new ProfilResource(true, 'Detail Data Profil!', $profil);
    }

    /**
     * Update an existing Profil.
     *
     * @OA\Put(
     *     path="/api/profil/{id}",
     *     tags={"Profil"},
     *     summary="Update a Profil by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="nama", type="string", example="Jane Doe"),
     *                 @OA\Property(property="username", type="string", example="janedoe"),
     *                 @OA\Property(property="password", type="string", format="password", description="User password, minimum 8 characters"),
     *                 @OA\Property(property="alamat", type="string", example="123 Example St"),
     *                 @OA\Property(property="email", type="string", example="jane.doe@example.com"),
     *                 @OA\Property(
     *                     property="logo",
     *                     type="string",
     *                     format="binary",
     *                     description="Logo image file"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profil updated",
     *         @OA\JsonContent(ref="#/components/schemas/Profil")
     *     ),
     *     @OA\Response(response=404, description="Profil not found")
     * )
     */



    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'nullable|string',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
            'alamat' => 'nullable|string',
            'email' => 'nullable|email',
            'logo' => 'nullable',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $profil = Profil::find($id);

        $old_password = $profil->password;
        if ($request->password == null) {
            $profil->password = $old_password;
        } else {
            $profil->password = Hash::make($request->password);
        }

        if ($request->hasFile('logo')) {
            if ($profil->logo && file_exists(public_path('uploads/logos/' . $profil->logo))) {
                unlink(public_path('uploads/logos/' . $profil->logo));
            }

            $file = $request->file('logo');
            $logoName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/logos'), $logoName);
            $profil->logo = $logoName;
        }

        $profil->nama = $request->nama ?? $profil->nama;
        $profil->username = $request->username ?? $profil->username;
        $profil->alamat = $request->alamat ?? $profil->alamat;
        $profil->email = $request->email ?? $profil->email;

        $profil->save();

        return new ProfilResource(true, 'Data Profil Berhasil Diubah!', $profil);
    }

    /**
     * Delete a Profil.
     *
     * @OA\Delete(
     *     path="/api/profil/{id}",
     *     tags={"Profil"},
     *     summary="Delete a Profil by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=204, description="Profil deleted"),
     *     @OA\Response(response=404, description="Profil not found")
     * )
     */
    public function destroy($id)
    {
        $profil = Profil::find($id);
        $profil->delete();
        return new ProfilResource(true, 'Data Profil Berhasil Dihapus!', null);
    }
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="User login",
     *     description="Authenticate user and return token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username", "password"},
     *             @OA\Property(property="username", type="string", example="user123"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(property="token", type="string", example="1|abc123..."),
     *             @OA\Property(property="data", type="object", 
     *                 @OA\Property(property="id_profil", type="string", example="id123"),
     *                 @OA\Property(property="username", type="string", example="user123"),
     *                 @OA\Property(property="nama", type="string", example="John Doe"),
     *                 @OA\Property(property="id_role", type="string", example="role123")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Login failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Login failed, invalid credentials")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $profil = Profil::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        if (!$profil || !Hash::check($request->password, $profil->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed, invalid credentials',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => $profil->load('role:id_role,role')->makeHidden(['password', 'created_at', 'updated_at']),
        ]);
    }
    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Auth"},
     *     summary="User logout",
     *     description="Revoke user's token",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logged out successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Logged out successfully")
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
