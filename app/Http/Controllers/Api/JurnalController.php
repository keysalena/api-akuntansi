<?php

namespace App\Http\Controllers\Api;

use App\Models\Jurnal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\JurnalResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API Documentation",
 *     description="API documentation for managing Jurnal resources"
 * )
 *
 * @OA\Tag(
 *     name="Jurnal",
 *     description="API Endpoints for Jurnal"
 * )
 */

/**
 * @OA\Schema(
 *     schema="Jurnal",
 *     @OA\Property(property="id_jurnal", type="string", example="jurnal_123"),
 *     @OA\Property(property="id_tipe_jurnal", type="string", example="tipe_123"),
 *     @OA\Property(property="tanggal", type="string", format="date-time", example="2024-01-01 10:00:00"),
 *     @OA\Property(property="nama_transaksi", type="string", example="Purchase Supplies"),
 *     @OA\Property(property="nominal", type="integer", example=1000),
 *     @OA\Property(property="id_debit", type="string", example="debit_123"),
 *     @OA\Property(property="id_kredit", type="string", example="kredit_123"),
 *     @OA\Property(property="id_profil", type="string", nullable=true, example="profil_123")
 * )
 */
class JurnalController extends Controller
{
    /**
     * Display a listing of Jurnal.
     *
     * @OA\Get(
     *     path="/api/jurnal",
     *     tags={"Jurnal"},
     *     summary="Get list of Jurnal",
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Jurnal"))
     *     )
     * )
     */
    public function index()
    {
        $jurnal = Jurnal::with([
            'debitAccount:id_data_akun,kode,nama',
            'kreditAccount:id_data_akun,kode,nama',
            'tipe_jurnal:id_tipe_jurnal,nama'
        ])->orderBy('tanggal', 'asc')->get();

        return new JurnalResource(true, 'List Data Jurnals', $jurnal);
    }

    /**
     * Store a new Jurnal.
     *
     * @OA\Post(
     *     path="/api/jurnal",
     *     tags={"Jurnal"},
     *     summary="Create a new Jurnal",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="id_tipe_jurnal", type="string", example="tipe_123"),
     *             @OA\Property(property="tanggal", type="string", format="date-time", example="2024-01-01 10:00:00"),
     *             @OA\Property(property="nama_transaksi", type="string", example="Purchase Supplies"),
     *             @OA\Property(property="nominal", type="integer", example=1000),
     *             @OA\Property(property="id_debit", type="string", example="debit_123"),
     *             @OA\Property(property="id_kredit", type="string", example="kredit_123"),
     *             @OA\Property(property="id_profil", type="string", nullable=true, example="profil_123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Jurnal created",
     *         @OA\JsonContent(ref="#/components/schemas/Jurnal")
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_tipe_jurnal' => 'required|string',
            'tanggal' => 'required|date',
            'nama_transaksi' => 'required|string',
            'nominal' => 'required|integer',
            'id_debit' => 'required|string',
            'id_kredit' => 'required|string',
            'id_profil' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $jurnal = Jurnal::create($request->all());
        return new JurnalResource(true, 'Data Jurnal Berhasil Ditambahkan!', $jurnal);
    }

    /**
     * Display a Jurnal by ID.
     *
     * @OA\Get(
     *     path="/api/jurnal/{id}",
     *     tags={"Jurnal"},
     *     summary="Get a Jurnal by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/Jurnal")
     *     ),
     *     @OA\Response(response=404, description="Jurnal not found")
     * )
     */
    public function show($id)
    {
        $jurnal = Jurnal::find($id);
        return new JurnalResource(true, 'Detail Data Jurnal!', $jurnal);
    }
    /**
     * Display a Jurnal by id_data_akun.
     *
     * @OA\Get(
     *     path="/api/jurnal/data-akun/{id_data_akun}",
     *     tags={"Jurnal"},
     *     summary="Get Jurnal entries by id_data_akun",
     *     @OA\Parameter(
     *         name="id_data_akun",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail Data Jurnal!"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id_jurnal", type="string", example="6731d248a1fa2"),
     *                     @OA\Property(property="tanggal", type="string", format="date", example="2024-11-11"),
     *                     @OA\Property(property="nama_transaksi", type="string", example="Purchase Supplies"),
     *                     @OA\Property(property="nominal", type="integer", example=1000),
     *                     @OA\Property(property="debit_account", type="object",
     *                         @OA\Property(property="id_data_akun", type="string", example="67318320ba219"),
     *                         @OA\Property(property="kode", type="integer", example=302),
     *                         @OA\Property(property="nama", type="string", example="Ping")
     *                     ),
     *                     @OA\Property(property="kredit_account", type="object",
     *                         @OA\Property(property="id_data_akun", type="string", example="673183156e783"),
     *                         @OA\Property(property="kode", type="integer", example=202),
     *                         @OA\Property(property="nama", type="string", example="Coba")
     *                     ),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-11T09:45:44.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-11T09:52:34.000000Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Jurnal not found")
     * )
     */
    public function showId($id_data_akun)
    {
        $jurnal = Jurnal::with([
            'debitAccount:id_data_akun,kode,nama',
            'kreditAccount:id_data_akun,kode,nama',
            'tipe_jurnal:id_tipe_jurnal,nama'
        ])->whereHas('debitAccount', function ($query) use ($id_data_akun) {
            $query->where('id_data_akun', $id_data_akun);
        })->orWhereHas('kreditAccount', function ($query) use ($id_data_akun) {
            $query->where('id_data_akun', $id_data_akun);
        })->get();

        if ($jurnal->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Jurnal not found'
            ], 404);
        }

        return new JurnalResource(true, 'Detail Data Jurnal!', $jurnal);
    }
    /**
     * Display a Jurnal by id_data_akun and optional date range.
     *
     * @OA\Get(
     *     path="/api/jurnal/data-akun/{id_data_akun}/between-date",
     *     tags={"Jurnal"},
     *     summary="Get Jurnal entries by id_data_akun with optional date range",
     *     @OA\Parameter(
     *         name="id_data_akun",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-01-01")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-12-31")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail Data Jurnal!"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id_jurnal", type="string", example="6731d248a1fa2"),
     *                     @OA\Property(property="tanggal", type="string", format="date", example="2024-11-11"),
     *                     @OA\Property(property="nama_transaksi", type="string", example="Purchase Supplies"),
     *                     @OA\Property(property="nominal", type="integer", example=1000),
     *                     @OA\Property(property="debit_account", type="object",
     *                         @OA\Property(property="id_data_akun", type="string", example="67318320ba219"),
     *                         @OA\Property(property="kode", type="integer", example=302),
     *                         @OA\Property(property="nama", type="string", example="Ping")
     *                     ),
     *                     @OA\Property(property="kredit_account", type="object",
     *                         @OA\Property(property="id_data_akun", type="string", example="673183156e783"),
     *                         @OA\Property(property="kode", type="integer", example=202),
     *                         @OA\Property(property="nama", type="string", example="Coba")
     *                     ),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-11T09:45:44.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-11T09:52:34.000000Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Jurnal not found")
     * )
     */
    public function showByIdAndDate($id_data_akun, Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $jurnal = Jurnal::with([
            'debitAccount:id_data_akun,kode,nama',
            'kreditAccount:id_data_akun,kode,nama',
            'tipe_jurnal:id_tipe_jurnal,nama'
        ])
            ->where(function ($query) use ($id_data_akun) {
                $query->whereHas('debitAccount', function ($query) use ($id_data_akun) {
                    $query->where('id_data_akun', $id_data_akun);
                })
                    ->orWhereHas('kreditAccount', function ($query) use ($id_data_akun) {
                        $query->where('id_data_akun', $id_data_akun);
                    });
            });

        if ($startDate && $endDate) {
            $jurnal->whereBetween('tanggal', [$startDate, $endDate]);
        }

        $jurnal = $jurnal->get();

        if ($jurnal->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Jurnal not found'
            ], 404);
        }

        return new JurnalResource(true, 'Detail Data Jurnal!', $jurnal);
    }

    /**
     * Update an existing Jurnal.
     *
     * @OA\Put(
     *     path="/api/jurnal/{id}",
     *     tags={"Jurnal"},
     *     summary="Update a Jurnal by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="id_tipe_jurnal", type="string", example="tipe_123"),
     *             @OA\Property(property="tanggal", type="string", format="date-time", example="2024-01-01 10:00:00"),
     *             @OA\Property(property="nama_transaksi", type="string", example="Purchase Supplies"),
     *             @OA\Property(property="nominal", type="integer", example=1000),
     *             @OA\Property(property="id_debit", type="string", example="debit_123"),
     *             @OA\Property(property="id_kredit", type="string", example="kredit_123"),
     *             @OA\Property(property="id_profil", type="string", nullable=true, example="profil_123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Jurnal updated",
     *         @OA\JsonContent(ref="#/components/schemas/Jurnal")
     *     ),
     *     @OA\Response(response=404, description="Jurnal not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_tipe_jurnal' => 'required|string',
            'tanggal' => 'required|date',
            'nama_transaksi' => 'required|string',
            'nominal' => 'required|integer',
            'id_debit' => 'required|string',
            'id_kredit' => 'required|string',
            'id_profil' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $jurnal = Jurnal::find($id);
        if (!$jurnal) {
            return response()->json(['message' => 'Jurnal not found'], 404);
        }

        $jurnal->update([
            'id_tipe_jurnal' => $request->id_tipe_jurnal,
            'tanggal' => $request->tanggal,
            'nama_transaksi' => $request->nama_transaksi,
            'nominal' => $request->nominal,
            'id_debit' => $request->id_debit,
            'id_kredit' => $request->id_kredit,
            'id_profil' => $request->id_profil,
        ]);

        return new JurnalResource(true, 'Data Jurnal Berhasil Diubah!', $jurnal);
    }

    /**
     * Delete a Jurnal.
     *
     * @OA\Delete(
     *     path="/api/jurnal/{id}",
     *     tags={"Jurnal"},
     *     summary="Delete a Jurnal by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=204, description="Jurnal deleted"),
     *     @OA\Response(response=404, description="Jurnal not found")
     * )
     */
    public function destroy($id)
    {
        $jurnal = Jurnal::find($id);
        $jurnal->delete();
        return new JurnalResource(true, 'Data Jurnal Berhasil Dihapus!', null);
    }
}
