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
     * Muestra una lista de los recursos.
     */
    public function index(Request $request): JsonResponse
    {
        // Obtiene el número de elementos por página desde la query string, por defecto 15
        $perPage = $request->query('per_page', 15);
        // Pagina los productos
        $productos = Producto::paginate($perPage);
        // Retorna los productos en formato JSON
        return response()->json($productos);
    }

    /**
     * Almacena un nuevo recurso en el almacenamiento.
     */
    public function store(Request $request): JsonResponse
    {
        // Valida la solicitud
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

        // Si la validación falla, retorna los errores
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Procesa y guarda la imagen si existe
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('imagenes', 'public');
            $fileUrl = url('storage/' . $filePath);
        }

        // Crea un nuevo producto con los datos validados
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

        // Retorna el producto creado con el código de estado 201 (Created)
        return response()->json($producto, 201);
    }

    /**
     * Muestra el recurso especificado.
     */
    public function show($id): JsonResponse
    {
        // Busca el producto por su ID
        $producto = Producto::find($id);
        // Si no se encuentra, retorna un mensaje de error
        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        // Retorna el producto encontrado en formato JSON
        return response()->json($producto);
    }

    /**
     * Actualiza el recurso especificado en el almacenamiento.
     */
    public function update(Request $request, Producto $producto): JsonResponse
    {
        // Valida la solicitud
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
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'denominacion_id' => 'required|exists:denominaciones,id',
        ]);

        // Si la validación falla, retorna los errores
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Procesa y guarda la nueva imagen si se ha cargado
        if ($request->hasFile('file')) {
            // Elimina la imagen antigua si existe
            if ($producto->file) {
                Storage::disk('public')->delete($producto->file);
            }

            // Guarda la nueva imagen
            $filePath = $request->file('file')->store('imagenes', 'public');
            $producto->imagen = $filePath;
        }

        // Actualiza el producto con los datos validados
        $producto->update($validator->validated());

        // Retorna el producto actualizado en formato JSON
        return response()->json($producto);
    }

    /**
     * Elimina el recurso especificado del almacenamiento.
     */
    public function destroy($id): JsonResponse
    {
        // Busca el producto por su ID
        $producto = Producto::find($id);
        // Si no se encuentra, retorna un mensaje de error
        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        // Elimina la imagen asociada si existe
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

        // Elimina el producto
        $producto->delete();

        // Retorna una respuesta vacía con el código de estado 204 (No Content)
        return response()->json(null, 204);
    }
}
