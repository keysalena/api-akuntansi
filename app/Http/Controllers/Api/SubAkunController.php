<?php

namespace App\Http\Controllers\Api;

use App\Models\SubAkun;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubAkunResource;
use App\Models\Akun;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API Documentation",
 *     description="API documentation for managing SubAkun resources"
 * )
 *
 * @OA\Tag(
 *     name="SubAkun",
 *     description="API Endpoints for SubAkun"
 * )
 */

/**
 * @OA\Schema(
 *     schema="SubAkun",
 *     @OA\Property(property="id_sub_akun", type="string", example="sub_akun_123"),
 *     @OA\Property(property="id_akun", type="string", example="akun_123"),
 *     @OA\Property(property="kode", type="integer", example=101),
 *     @OA\Property(property="nama", type="string", example="John Doe")
 * )
 */
class SubAkunController extends Controller
{
    /**
     * Display a listing of SubAkun.
     *
     * @OA\Get(
     *     path="/api/sub_akun",
     *     tags={"SubAkun"},
     *     summary="Get list of SubAkun",
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/SubAkun"))
     *     )
     * )
     */
    public function index()
    {
        $sub_akun = SubAkun::with(['akun:id_akun,kode,nama'])->orderBy('kode', 'asc')->paginate(100);
        return new SubAkunResource(true, 'List Data SubAkuns', $sub_akun);
    }

    /**
     * Store a new SubAkun.
     *
     * @OA\Post(
     *     path="/api/sub_akun",
     *     tags={"SubAkun"},
     *     summary="Create a new SubAkun",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama", type="string", example="John Doe"),
     *             @OA\Property(property="id_akun", type="string", example="akun_123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="SubAkun created",
     *         @OA\JsonContent(ref="#/components/schemas/SubAkun")
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_akun' => 'required',
            'nama' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $akun = Akun::find($request->id_akun);
        if ($request->nama == $akun->nama) {
            $new_kode = $akun->kode;
        } else {
            $base_kode = $akun ? $akun->kode : 0;
            $count = SubAkun::where('id_akun', $request->id_akun)->count();
            $new_kode = ($count == 0) ? ($base_kode + 1) : (10 * $count + $base_kode);
        }

        $sub_akun = SubAkun::create([
            'id_akun' => $request->id_akun,
            'nama' => $request->nama,
            'kode' => $new_kode,
        ]);

        return new SubAkunResource(true, 'Data SubAkun Berhasil Ditambahkan!', $sub_akun);
    }

    /**
     * Display a SubAkun by ID.
     *
     * @OA\Get(
     *     path="/api/sub_akun/{id}",
     *     tags={"SubAkun"},
     *     summary="Get a SubAkun by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/SubAkun")
     *     ),
     *     @OA\Response(response=404, description="SubAkun not found")
     * )
     */
    public function show($id)
    {
        $sub_akun = SubAkun::with(['akun:id_akun,kode,nama'])
            ->find($id);

        return new SubAkunResource(true, 'Detail Data SubAkun!', $sub_akun);
    }

    /**
     * Update an existing SubAkun.
     *
     * @OA\Put(
     *     path="/api/sub_akun/{id}",
     *     tags={"SubAkun"},
     *     summary="Update a SubAkun by ID",
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
     *             @OA\Property(property="kode", type="integer", example="123"),
     *             @OA\Property(property="id_akun", type="string", example="akun_456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="SubAkun updated",
     *         @OA\JsonContent(ref="#/components/schemas/SubAkun")
     *     ),
     *     @OA\Response(response=404, description="SubAkun not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_akun' => 'required',
            'nama' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $sub_akun = SubAkun::find($id);

        $old_id_akun = $sub_akun->id_akun;

        if ($request->id_akun != $old_id_akun) {
            $akun = Akun::find($request->id_akun);
            $base_kode = $akun ? $akun->kode : 0;
            $count = SubAkun::where('id_akun', $request->id_akun)->count();
            $new_kode = ($count == 0) ? ($base_kode + 1) : (10 * $count + $base_kode);

            $sub_akun->kode = $new_kode;
        }

        $sub_akun->id_akun = $request->id_akun;
        $sub_akun->nama = $request->nama;

        $sub_akun->save();

        return new SubAkunResource(true, 'Data SubAkun Berhasil Diubah!', $sub_akun);
    }

    /**
     * Delete a SubAkun.
     *
     * @OA\Delete(
     *     path="/api/sub_akun/{id}",
     *     tags={"SubAkun"},
     *     summary="Delete a SubAkun by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=204, description="SubAkun deleted"),
     *     @OA\Response(response=404, description="SubAkun not found")
     * )
     */
    public function destroy($id)
    {
        $sub_akun = SubAkun::find($id);
        $sub_akun->delete();
        return new SubAkunResource(true, 'Data SubAkun Berhasil Dihapus!', null);
    }
}
