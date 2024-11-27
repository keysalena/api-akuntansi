<?php

namespace App\Http\Controllers\Api;

use App\Models\TipeJurnal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TipeJurnalResource;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API Documentation",
 *     description="API documentation for managing TipeJurnal resources"
 * )
 *
 * @OA\Tag(
 *     name="TipeJurnal",
 *     description="API Endpoints for TipeJurnal"
 * )
 */

/**
 * @OA\Schema(
 *     schema="TipeJurnal",
 *     @OA\Property(property="id_tipe", type="string", example="akun_123"),
 *     @OA\Property(property="nama", type="string", example="John Doe")
 * )
 */

class TipeJurnalController extends Controller
{
    /**
     * Display a listing of TipeJurnals.
     *
     * @OA\Get(
     *     path="/api/tipe_jurnal",
     *     tags={"TipeJurnal"},
     *     summary="Get list of TipeJurnals",
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/TipeJurnal"))
     *     )
     * )
     */
    public function index()
    {
        $tipe_jurnal = TipeJurnal::orderBy('created_at', 'asc')->paginate(50);
        return new TipeJurnalResource(true, 'List Data TipeJurnals', $tipe_jurnal);
    }

     /**
     * Store a new TipeJurnal.
     *
     * @OA\Post(
     *     path="/api/tipe_jurnal",
     *     tags={"TipeJurnal"},
     *     summary="Create a new TipeJurnal",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama", type="string", example="John Doe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="TipeJurnal created",
     *         @OA\JsonContent(ref="#/components/schemas/TipeJurnal")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $tipe_jurnal = TipeJurnal::create([
            'nama'   => $request->nama,
        ]);

        return new TipeJurnalResource(true, 'Data TipeJurnal Berhasil Ditambahkan!', $tipe_jurnal);
    }

    /**
     * Display an TipeJurnal by ID.
     *
     * @OA\Get(
     *     path="/api/tipe_jurnal/{id}",
     *     tags={"TipeJurnal"},
     *     summary="Get an TipeJurnal by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/TipeJurnal")
     *     ),
     *     @OA\Response(response=404, description="TipeJurnal not found")
     * )
     */
    public function show($id)
    {
        $tipe_jurnal = TipeJurnal::find($id);
        return new TipeJurnalResource(true, 'Detail Data TipeJurnal!', $tipe_jurnal);
    }

     /**
     * Update an existing TipeJurnal.
     *
     * @OA\Put(
     *     path="/api/tipe_jurnal/{id}",
     *     tags={"TipeJurnal"},
     *     summary="Update an TipeJurnal by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama", type="string", example="Jane Doe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="TipeJurnal updated",
     *         @OA\JsonContent(ref="#/components/schemas/TipeJurnal")
     *     ),
     *     @OA\Response(response=404, description="TipeJurnal not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $tipe_jurnal = TipeJurnal::find($id);

        $tipe_jurnal->update([
            'nama'   => $request->nama,
        ]);

        return new TipeJurnalResource(true, 'Data TipeJurnal Berhasil Diubah!', $tipe_jurnal);
    }

    /**
     * Delete an TipeJurnal.
     *
     * @OA\Delete(
     *     path="/api/tipe_jurnal/{id}",
     *     tags={"TipeJurnal"},
     *     summary="Delete an TipeJurnal by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=204, description="TipeJurnal deleted"),
     *     @OA\Response(response=404, description="TipeJurnal not found")
     * )
     */
    public function destroy($id)
    {
        $tipe_jurnal = TipeJurnal::find($id);
        $tipe_jurnal->delete();
        return new TipeJurnalResource(true, 'Data TipeJurnal Berhasil Dihapus!', null);
    }
}
