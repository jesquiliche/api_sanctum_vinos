<?php

namespace App\Http\Controllers;

use App\Models\Denominacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DenominacionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/denominacion",
     *     operationId="getDenominacionList",
     *     tags={"Denominacion"},
     *     summary="Get list of denominaciones",
     *     description="Returns list of denominaciones",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nombre", type="string", example="Denominacion 1"),
     *             @OA\Property(property="descripcion", type="string", example="Descripción de la denominacion 1")
     *         ))
     *     )
     * )
     */
    public function index()
    {
        $denominaciones = Denominacion::all();
        return response()->json($denominaciones);
    }

    /**
     * @OA\Post(
     *     path="/api/denominacion",
     *     operationId="storeDenominacion",
     *     tags={"Denominacion"},
     *     summary="Store a new denominacion",
     *     description="Stores a new denominacion and returns it",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Nueva Denominacion"),
     *             @OA\Property(property="descripcion", type="string", example="Descripción de la nueva denominacion")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nombre", type="string", example="Nueva Denominacion"),
     *             @OA\Property(property="descripcion", type="string", example="Descripción de la nueva denominacion")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(type="object", @OA\Property(property="errors", type="object"))
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $denominacion = Denominacion::create($validator->validated());

        return response()->json($denominacion, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/denominacion/{id}",
     *     operationId="getDenominacionById",
     *     tags={"Denominacion"},
     *     summary="Get denominacion by ID",
     *     description="Returns a single denominacion",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of denominacion to return",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nombre", type="string", example="Denominacion 1"),
     *             @OA\Property(property="descripcion", type="string", example="Descripción de la denominacion 1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Denominacion not found"
     *     )
     * )
     */
    public function show($id)
    {
        $denominacion = Denominacion::findOrFail($id);
        return response()->json($denominacion);
    }

    /**
     * @OA\Put(
     *     path="/api/denominacion/{id}",
     *     operationId="updateDenominacion",
     *     tags={"Denominacion"},
     *     summary="Update an existing denominacion",
     *     description="Updates an existing denominacion and returns it",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of denominacion to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Denominacion Actualizada"),
     *             @OA\Property(property="descripcion", type="string", example="Descripción de la denominacion actualizada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nombre", type="string", example="Denominacion Actualizada"),
     *             @OA\Property(property="descripcion", type="string", example="Descripción de la denominacion actualizada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Denominacion not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(type="object", @OA\Property(property="errors", type="object"))
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $denominacion = Denominacion::find($id);
        if ($denominacion) {
            $denominacion->update($request->all());
            return response()->json($denominacion);
        } else {
            return response()->json(['message' => 'Denominacion no encontrada'], 404);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/denominacion/{id}",
     *     operationId="deleteDenominacion",
     *     tags={"Denominacion"},
     *     summary="Delete a denominacion",
     *     description="Deletes a single denominacion",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of denominacion to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Denominacion not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $denominacion = Denominacion::findOrFail($id);
        $denominacion->delete();

        return response()->json(null, 204);
    }
}
