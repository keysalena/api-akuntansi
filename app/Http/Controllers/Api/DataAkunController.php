<?php

namespace App\Http\Controllers\Api;

use App\Models\DataAkun;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataAkunResource;
use App\Models\SubAkun;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API Documentation",
 *     description="API documentation for managing DataAkun resources"
 * )
 *
 * @OA\Tag(
 *     name="DataAkun",
 *     description="API Endpoints for DataAkun"
 * )
 */

/**
 * @OA\Schema(
 *     schema="DataAkun",
 *     @OA\Property(property="id_data_akun", type="string", example="sub_akun_123"),
 *     @OA\Property(property="id_sub_akun", type="string", example="akun_123"),
 *     @OA\Property(property="kode", type="integer", example=101),
 *     @OA\Property(property="nama", type="string", example="John Doe")
 * )
 */
class DataAkunController extends Controller
{
    /**
     * Display a listing of DataAkun.
     *
     * @OA\Get(
     *     path="/api/data_akun",
     *     tags={"DataAkun"},
     *     summary="Get list of DataAkun",
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/DataAkun"))
     *     )
     * )
     */
    public function index()
    {
        $data_akun = DataAkun::with(['subAkun.akun'])
            ->orderBy('kode', 'asc')
            ->paginate(100);

        return new DataAkunResource(true, 'List Data DataAkuns', $data_akun);
    }

    /**
     * Store a new DataAkun.
     *
     * @OA\Post(
     *     path="/api/data_akun",
     *     tags={"DataAkun"},
     *     summary="Create a new DataAkun",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama", type="string", example="John Doe"),
     *             @OA\Property(property="id_sub_akun", type="string", example="akun_123"),
     *             @OA\Property(property="debit", type="integer", example="20000"),
     *             @OA\Property(property="kredit", type="integer", example="20000"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="DataAkun created",
     *         @OA\JsonContent(ref="#/components/schemas/DataAkun")
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_sub_akun' => 'required',
            'nama' => 'required',
            'debit' => 'nullable',
            'kredit' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $sub_akun = SubAkun::find($request->id_sub_akun);
        $base_kode = $sub_akun ? $sub_akun->kode : 0;
        $count = DataAkun::where('id_sub_akun', $request->id_sub_akun)->count();

        if ($count == 0) {
            $new_kode = $base_kode + 1;
        } elseif ($count == 1) {
            $new_kode = 2 + $base_kode;
        } else {
            $new_kode = $count + 1 + $base_kode;
        }

        $data_akun = DataAkun::create([
            'id_sub_akun' => $request->id_sub_akun,
            'nama' => $request->nama,
            'debit' => $request->debit,
            'kredit' => $request->kredit,
            'kode' => $new_kode,
        ]);

        return new DataAkunResource(true, 'Data DataAkun Berhasil Ditambahkan!', $data_akun);
    }

    /**
     * Display a DataAkun by ID.
     *
     * @OA\Get(
     *     path="/api/data_akun/{id}",
     *     tags={"DataAkun"},
     *     summary="Get a DataAkun by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/DataAkun")
     *     ),
     *     @OA\Response(response=404, description="DataAkun not found")
     * )
     */
    public function show($id)
    {
        $data_akun = DataAkun::find($id);
        return new DataAkunResource(true, 'Detail Data DataAkun!', $data_akun);
    }

    /**
     * Update an existing DataAkun.
     *
     * @OA\Put(
     *     path="/api/data_akun/{id}",
     *     tags={"DataAkun"},
     *     summary="Update a DataAkun by ID",
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
     *             @OA\Property(property="id_sub_akun", type="string", example="akun_456"),
     *             @OA\Property(property="debit", type="integer", example="2000"),
     *             @OA\Property(property="kredit", type="integer", example="2000"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="DataAkun updated",
     *         @OA\JsonContent(ref="#/components/schemas/DataAkun")
     *     ),
     *     @OA\Response(response=404, description="DataAkun not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_sub_akun' => 'required',
            'nama' => 'required',
            'debit' => 'nullable',
            'kredit' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data_akun = DataAkun::find($id);

        $old_id_sub_akun = $data_akun->id_sub_akun;

        if ($request->id_sub_akun != $old_id_sub_akun) {
            $sub_akun = SubAkun::find($request->id_sub_akun);
            $base_kode = $sub_akun ? $sub_akun->kode : 0;
            $count = DataAkun::where('id_sub_akun', $request->id_sub_akun)->count();

            if ($count == 0) {
                $new_kode = $base_kode + 1;
            } elseif ($count == 1) {
                $new_kode = 2 + $base_kode;
            } else {
                $new_kode = $count + 1 + $base_kode;
            }

            $data_akun->kode = $new_kode;
        }

        $data_akun->id_sub_akun = $request->id_sub_akun;
        $data_akun->nama = $request->nama;
        $data_akun->debit = $request->debit;
        $data_akun->kredit = $request->kredit;

        $data_akun->save();

        return new DataAkunResource(true, 'Data DataAkun Berhasil Diubah!', $data_akun);
    }

    /**
     * Delete a DataAkun.
     *
     * @OA\Delete(
     *     path="/api/data_akun/{id}",
     *     tags={"DataAkun"},
     *     summary="Delete a DataAkun by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=204, description="DataAkun deleted"),
     *     @OA\Response(response=404, description="DataAkun not found")
     * )
     */
    public function destroy($id)
    {
        $data_akun = DataAkun::find($id);
        $data_akun->delete();
        return new DataAkunResource(true, 'Data DataAkun Berhasil Dihapus!', null);
    }
}
