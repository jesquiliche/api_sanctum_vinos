<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
       /**
     * @OA\Get(
     *     path="/api/producto",
     *     operationId="getProductoList",
     *     tags={"Producto"},
     *     summary="Obtener lista de productos",
     *     description="Devuelve una lista paginada de productos",
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Número de elementos por página",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre", type="string", example="Producto 1"),
     *                 @OA\Property(property="bodega", type="string", example="Bodega A"),
     *                 @OA\Property(property="descripcion", type="string", example="Descripción del producto 1"),
     *                 @OA\Property(property="maridaje", type="string", example="Maridaje del producto 1"),
     *                 @OA\Property(property="precio", type="number", format="float", example=20.5),
     *                 @OA\Property(property="graduacion", type="number", format="float", example=12.5),
     *                 @OA\Property(property="ano", type="integer", example=2023),
     *                 @OA\Property(property="sabor", type="string", example="Sabor del producto 1"),
     *                 @OA\Property(property="tipo_id", type="integer", example=1),
     *                 @OA\Property(property="denominacion_id", type="integer", example=1),
     *                 @OA\Property(property="imagen", type="string", example="http://example.com/storage/imagen.jpg")
     *             )),
     *             @OA\Property(property="links", type="object", @OA\Property(property="first", type="string"), @OA\Property(property="last", type="string"), @OA\Property(property="prev", type="string"), @OA\Property(property="next", type="string")),
     *             @OA\Property(property="meta", type="object", @OA\Property(property="current_page", type="integer"), @OA\Property(property="from", type="integer"), @OA\Property(property="last_page", type="integer"), @OA\Property(property="path", type="string"), @OA\Property(property="per_page", type="integer"), @OA\Property(property="to", type="integer"), @OA\Property(property="total", type="integer"))
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 15); // Número de elementos por página, por defecto 15
        $productos = Producto::paginate($perPage);
        return response()->json($productos);
    }

     /**
     * @OA\Post(
     *     path="/api/producto",
     *     operationId="storeProducto",
     *     tags={"Producto"},
     *     summary="Crear un nuevo producto",
     *     description="Crea un nuevo producto y almacena sus datos junto con una imagen",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="nombre",
     *                     type="string",
     *                     description="Nombre del producto",
     *                     example="Producto 1"
     *                 ),
     *                 @OA\Property(
     *                     property="bodega",
     *                     type="string",
     *                     description="Bodega del producto",
     *                     example="Bodega A"
     *                 ),
     *                 @OA\Property(
     *                     property="descripcion",
     *                     type="string",
     *                     description="Descripción del producto",
     *                     example="Descripción del producto 1"
     *                 ),
     *                 @OA\Property(
     *                     property="maridaje",
     *                     type="string",
     *                     description="Maridaje del producto",
     *                     example="Maridaje del producto 1"
     *                 ),
     *                 @OA\Property(
     *                     property="precio",
     *                     type="number",
     *                     format="float",
     *                     description="Precio del producto",
     *                     example=20.5
     *                 ),
     *                 @OA\Property(
     *                     property="graduacion",
     *                     type="number",
     *                     format="float",
     *                     description="Graduación del producto",
     *                     example=12.5
     *                 ),
     *                 @OA\Property(
     *                     property="ano",
     *                     type="integer",
     *                     description="Año del producto",
     *                     example=2023
     *                 ),
     *                 @OA\Property(
     *                     property="sabor",
     *                     type="string",
     *                     description="Sabor del producto",
     *                     example="Sabor del producto 1"
     *                 ),
     *                 @OA\Property(
     *                     property="tipo_id",
     *                     type="integer",
     *                     description="ID del tipo de producto",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="denominacion_id",
     *                     type="integer",
     *                     description="ID de la denominación del producto",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                     description="Imagen del producto"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Producto creado con éxito",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nombre", type="string", example="Producto 1"),
     *             @OA\Property(property="bodega", type="string", example="Bodega A"),
     *             @OA\Property(property="descripcion", type="string", example="Descripción del producto 1"),
     *             @OA\Property(property="maridaje", type="string", example="Maridaje del producto 1"),
     *             @OA\Property(property="precio", type="number", format="float", example=20.5),
     *             @OA\Property(property="graduacion", type="number", format="float", example=12.5),
     *             @OA\Property(property="ano", type="integer", example=2023),
     *             @OA\Property(property="sabor", type="string", example="Sabor del producto 1"),
     *             @OA\Property(property="tipo_id", type="integer", example=1),
     *             @OA\Property(property="denominacion_id", type="integer", example=1),
     *             @OA\Property(property="imagen", type="string", example="http://example.com/storage/imagen.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'bodega' => 'nullable|string|max:255',
            'descripcion' => 'required|string',
            'maridaje' => 'required|string',
            'precio' => 'required|numeric',
            'graduacion' => 'required|numeric',
            'ano' => 'nullable|integer',
            'sabor' => 'nullable|string|max:255',
            'tipo_id' => 'required|exists:tipos,id',
            'imagen' => 'nullable|string',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'denominacion_id' => 'required|exists:denominaciones,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Procesar y guardar la imagen
        $fileUrl = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('imagenes', 'public');
            $fileUrl = url('storage/' . $filePath);
        }

        $producto = Producto::create([
            'nombre' => $request->nombre,
            'bodega' => $request->bodega,
            'descripcion' => $request->descripcion,
            'maridaje' => $request->maridaje,
            'precio' => $request->precio,
            'graduacion' => $request->graduacion,
            'ano' => $request->ano,
            'sabor' => $request->sabor,
            'tipo_id' => $request->tipo_id,
            'imagen' => $fileUrl ?? null,
            'denominacion_id' => $request->denominacion_id,
        ]);

        return response()->json($producto, 201); // 201 Created
    }

    /**
     * Display the specified resource.
     */
        /**
     * @OA\Get(
     *     path="/api/producto/{id}",
     *     operationId="getProductoById",
     *     tags={"Producto"},
     *     summary="Obtener un producto por ID",
     *     description="Devuelve los datos de un producto específico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del producto",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nombre", type="string", example="Producto 1"),
     *             @OA\Property(property="bodega", type="string", example="Bodega A"),
     *             @OA\Property(property="descripcion", type="string", example="Descripción del producto 1"),
     *             @OA\Property(property="maridaje", type="string", example="Maridaje del producto 1"),
     *             @OA\Property(property="precio", type="number", format="float", example=20.5),
     *             @OA\Property(property="graduacion", type="number", format="float", example=12.5),
     *             @OA\Property(property="ano", type="integer", example=2023),
     *             @OA\Property(property="sabor", type="string", example="Sabor del producto 1"),
     *             @OA\Property(property="tipo_id", type="integer", example=1),
     *             @OA\Property(property="denominacion_id", type="integer", example=1),
     *             @OA\Property(property="imagen", type="string", example="http://example.com/storage/imagen.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Producto no encontrado")
     *         )
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        $producto = Producto::find($id);
        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        return response()->json($producto);
    }

/**
 * @OA\Put(
 *     path="/api/producto/{id}",
 *     operationId="updateProducto",
 *     tags={"Producto"},
 *     summary="Actualizar un producto existente",
 *     description="Actualiza los datos de un producto existente",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID del producto",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="nombre", type="string", example="Producto Actualizado"),
 *             @OA\Property(property="bodega", type="string", example="Bodega Actualizada"),
 *             @OA\Property(property="descripcion", type="string", example="Descripción del producto actualizado"),
 *             @OA\Property(property="maridaje", type="string", example="Maridaje del producto actualizado"),
 *             @OA\Property(property="precio", type="number", format="float", example=25.75),
 *             @OA\Property(property="graduacion", type="number", format="float", example=13.0),
 *             @OA\Property(property="ano", type="integer", example=2022),
 *             @OA\Property(property="sabor", type="string", example="Sabor del producto actualizado"),
 *             @OA\Property(property="tipo_id", type="integer", example=2),
 *             @OA\Property(property="denominacion_id", type="integer", example=2),
 *             @OA\Property(property="file", type="string", format="binary", description="Imagen del producto")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="nombre", type="string", example="Producto Actualizado"),
 *             @OA\Property(property="bodega", type="string", example="Bodega Actualizada"),
 *             @OA\Property(property="descripcion", type="string", example="Descripción del producto actualizado"),
 *             @OA\Property(property="maridaje", type="string", example="Maridaje del producto actualizado"),
 *             @OA\Property(property="precio", type="number", format="float", example=25.75),
 *             @OA\Property(property="graduacion", type="number", format="float", example=13.0),
 *             @OA\Property(property="ano", type="integer", example=2022),
 *             @OA\Property(property="sabor", type="string", example="Sabor del producto actualizado"),
 *             @OA\Property(property="tipo_id", type="integer", example=2),
 *             @OA\Property(property="denominacion_id", type="integer", example=2),
 *             @OA\Property(property="imagen", type="string", example="http://example.com/storage/imagen.jpg")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error de validación",
 *         @OA\JsonContent(type="object", @OA\Property(property="errors", type="object"))
 *     )
 * )
 */
public function update(Request $request, Producto $producto): JsonResponse
{
    $validator = Validator::make($request->all(), [
        'nombre' => 'required|string|max:255',
        'bodega' => 'nullable|string|max:255',
        'descripcion' => 'required|string',
        'maridaje' => 'required|string',
        'precio' => 'required|numeric',
        'graduacion' => 'required|numeric',
        'ano' => 'nullable|integer',
        'sabor' => 'nullable|string|max:255',
        'tipo_id' => 'required|exists:tipos,id',
      //  'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'denominacion_id' => 'required|exists:denominaciones,id',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    // Procesar y guardar la nueva imagen si se ha cargado
    if ($request->hasFile('file')) {
        // Eliminar la imagen antigua si existe
        if ($producto->file) {
            Storage::disk('public')->delete($producto->file);
        }

        // Guardar la nueva imagen
        $filePath = $request->file('file')->store('imagenes', 'public');
        $producto->imagen = $filePath;
    }

    $producto->update($validator->validated());

    return response()->json($producto);
}
    /**
     * @OA\Delete(
     *     path="/api/producto/{id}",
     *     operationId="deleteProducto",
     *     tags={"Producto"},
     *     summary="Eliminar un producto",
     *     description="Elimina un producto existente por su ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del producto",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No Content",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Producto no encontrado")
     *         )
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        $producto = Producto::find($id);
        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        // Eliminar la imagen asociada si existe
        if ($producto->imagen) {
            $imagePath = 'imagenes/' . basename($producto->imagen);
            if (Storage::disk('public')->exists($imagePath)) {
                $deleted = Storage::disk('public')->delete($imagePath);
                if (!$deleted) {
                    return response()->json(['message' => 'Error al eliminar la imagen'], 500);
                }
            } else {
                return response()->json(['message' => 'Imagen no encontrada'], 404);
            }
        }
        
        $producto->delete();

        return response()->json(null, 204); // 204 No Content
    }

}
