<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tipo",
     *     operationId="getTiposList",
     *     tags={"Tipos"},
     *     summary="Get list of tipos",
     *     description="Returns list of tipos",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nombre", type="string", example="Tipo 1"),
     *             @OA\Property(property="descripcion", type="string", example="Descripción del tipo 1")
     *         ))
     *     )
     * )
     */
    public function index()
    {
        $tipos = Tipo::all();
        return response()->json($tipos);
    }

    /**
     * @OA\Post(
     *     path="/api/tipo",
     *     operationId="storeTipo",
     *     tags={"Tipos"},
     *     summary="Store a new tipo",
     *     description="Stores a new tipo and returns it",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Nuevo Tipo"),
     *             @OA\Property(property="descripcion", type="string", example="Descripción del nuevo tipo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nombre", type="string", example="Nuevo Tipo"),
     *             @OA\Property(property="descripcion", type="string", example="Descripción del nuevo tipo")
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

        $tipo = Tipo::create($validator->validated());

        return response()->json($tipo, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/tipo/{id}",
     *     operationId="getTipoById",
     *     tags={"Tipos"},
     *     summary="Get tipo by ID",
     *     description="Returns a single tipo",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of tipo to return",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nombre", type="string", example="Tipo 1"),
     *             @OA\Property(property="descripcion", type="string", example="Descripción del tipo 1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tipo not found"
     *     )
     * )
     */
    public function show($id)
    {
        $tipo = Tipo::findOrFail($id);
        return response()->json($tipo);
    }

    /**
     * @OA\Put(
     *     path="/api/tipo/{id}",
     *     operationId="updateTipo",
     *     tags={"Tipos"},
     *     summary="Update an existing tipo",
     *     description="Updates an existing tipo and returns it",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of tipo to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Tipo Actualizado"),
     *             @OA\Property(property="descripcion", type="string", example="Descripción del tipo actualizado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nombre", type="string", example="Tipo Actualizado"),
     *             @OA\Property(property="descripcion", type="string", example="Descripción del tipo actualizado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tipo not found"
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

        $tipo = Tipo::find($id);
        if ($tipo) {
            $tipo->update($request->all());
            return response()->json($tipo);
        } else {
            return response()->json(['message' => 'Tipo no encontrada'], 404);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/tipo/{id}",
     *     operationId="deleteTipo",
     *     tags={"Tipos"},
     *     summary="Delete a tipo",
     *     description="Deletes a single tipo",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of tipo to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tipo not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $tipo = Tipo::find($id);
        if ($tipo) {
            $tipo->delete();
            return response()->json(null, 204);
        }
        return response()->json(null, 404);
    }
}
?>
