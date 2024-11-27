<?php

namespace App\Http\Controllers\Api;

use App\Models\Akun;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AkunResource;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API Documentation",
 *     description="API documentation for managing Akun resources"
 * )
 *
 * @OA\Tag(
 *     name="Akun",
 *     description="Akun"
 * )
 *
 * @OA\Schema(
 *     schema="Akun",
 *     @OA\Property(property="id_akun", type="string", example="unique_id_123"),
 *     @OA\Property(property="nama", type="string", example="John Doe"),
 *     @OA\Property(property="kode", type="integer", example=123)
 * )
 */

class AkunController extends Controller
{
    /**
     * Display a listing of Akun.
     *
     * @OA\Get(
     *     path="/api/akun",
     *     tags={"Akun"},
     *     summary="Get list of Akun",
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Akun"))
     *     )
     * )
     */
    public function index()
    {
        $akun = Akun::orderBy('kode', 'asc')->paginate(100);
        return new AkunResource(true, 'List Data Akun', $akun);
    }

    /**
     * Store a new Akun.
     *
     * @OA\Post(
     *     path="/api/akun",
     *     tags={"Akun"},
     *     summary="Create a new Akun",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama", type="string", example="John Doe"),
     *             @OA\Property(property="kode", type="integer", example=123)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Akun created",
     *         @OA\JsonContent(ref="#/components/schemas/Akun")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode'     => 'required',
            'nama'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $akun = Akun::create([
            'kode'     => $request->kode,
            'nama'   => $request->nama,
        ]);

        return new AkunResource(true, 'Data Akun Berhasil Ditambahkan!', $akun);
    }

    /**
     * Display an Akun by ID.
     *
     * @OA\Get(
     *     path="/api/akun/{id}",
     *     tags={"Akun"},
     *     summary="Get an Akun by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/Akun")
     *     ),
     *     @OA\Response(response=404, description="Akun not found")
     * )
     */
    public function show($id)
    {
        $akun = Akun::find($id);
        return new AkunResource(true, 'Detail Data Akun!', $akun);
    }

    /**
     * Update an existing Akun.
     *
     * @OA\Put(
     *     path="/api/akun/{id}",
     *     tags={"Akun"},
     *     summary="Update an Akun by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama", type="string", example="Jane Doe"),
     *             @OA\Property(property="kode", type="integer", example=456)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Akun updated",
     *         @OA\JsonContent(ref="#/components/schemas/Akun")
     *     ),
     *     @OA\Response(response=404, description="Akun not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode'     => 'required',
            'nama'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $akun = Akun::find($id);

        $akun->update([
            'kode'     => $request->kode,
            'nama'   => $request->nama,
        ]);

        return new AkunResource(true, 'Data Akun Berhasil Diubah!', $akun);
    }

    /**
     * Delete an Akun.
     *
     * @OA\Delete(
     *     path="/api/akun/{id}",
     *     tags={"Akun"},
     *     summary="Delete an Akun by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=204, description="Akun deleted"),
     *     @OA\Response(response=404, description="Akun not found")
     * )
     */
    public function destroy($id)
    {
        $akun = Akun::find($id);
        $akun->delete();
        return new AkunResource(true, 'Data Akun Berhasil Dihapus!', null);
    }
}
