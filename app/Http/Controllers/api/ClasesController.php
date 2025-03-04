<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\MiController;
use App\Models\Clases;

/**
 * @OA\Info(title="API de Clases", version="1.0")
 */
class ClasesController extends MiController
{
    /**
     * @OA\Get(
     *     path="/api/clases",
     *     summary="Obtener todas las clases",
     *     @OA\Parameter(
     *         name="paginate",
     *         in="query",
     *         description="Número de resultados por página",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="sala",
     *         in="query",
     *         description="Filtrar por número de sala",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Lista de clases obtenida correctamente"),
     *     @OA\Response(response=400, description="Error en la solicitud")
     * )
     */
    public function index(Request $request)
    {
        if (isset($request->paginate)) return $this->paginar($request);
        if (isset($request->sala)) return $this->filtrarSala(intval($request->sala));
        return response()->json(Clases::all(), 200);
    }

    /**
     * @OA\Get(
     *     path="/api/clases/{id}",
     *     summary="Obtener los detalles de una clase",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la clase",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Detalles de la clase"),
     *     @OA\Response(response=404, description="Clase no encontrada")
     * )
     */
    public function show($id)
    {
        $clase = Clases::find($id);
        $this->check404($clase);
        return response()->json($clase, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/clases",
     *     summary="Crear una nueva clase",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "hora", "sala", "imagen"},
     *             @OA\Property(property="nombre", type="string", example="Clase de Yoga"),
     *             @OA\Property(property="hora", type="string", format="time", example="14:30"),
     *             @OA\Property(property="sala", type="integer", example=1),
     *             @OA\Property(property="imagen", type="string", example="url_de_la_imagen")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Clase creada con éxito"),
     *     @OA\Response(response=422, description="Error en la validación de datos")
     * )
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string',
                'hora' => 'required|date_format:H:i',
                'sala' => 'required|integer',
                'imagen' => 'required|string'
            ]);
            $existe = Clases::where('nombre', $validated['nombre'])->first();
            if ($existe) {
                return response()->json($existe, 201);
            }
            $new = Clases::create($validated);
            return response()->json($new, 201);
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 422);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/clases/{id}",
     *     summary="Actualizar una clase existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la clase a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "hora", "sala", "imagen"},
     *             @OA\Property(property="nombre", type="string", example="Clase de Pilates"),
     *             @OA\Property(property="hora", type="string", format="time", example="16:00"),
     *             @OA\Property(property="sala", type="integer", example=2),
     *             @OA\Property(property="imagen", type="string", example="url_actualizada")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Clase actualizada correctamente"),
     *     @OA\Response(response=404, description="Clase no encontrada"),
     *     @OA\Response(response=422, description="Error en la validación de datos")
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $clase = Clases::find($id);
            $this->check404($clase);

            $request->validate([
                'nombre' => 'required',
                'hora' => 'required|date_format:H:i',
                'sala' => 'required|integer',
                'imagen' => 'required'
            ]);

            $clase->fill($request->all());
            $clase->save();
            return response()->json([$clase], 200);
        } catch (\Throwable $th) {
            response()->json([
                'status' => 404,
                'message' => 'No se ha encontrado una clase con ese id'
            ], 404)->send();
            die();
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/clases/{id}",
     *     summary="Eliminar una clase",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la clase a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Clase eliminada correctamente"),
     *     @OA\Response(response=404, description="Clase no encontrada")
     * )
     */
    public function destroy($id)
    {
        try {
            $clase = Clases::find($id);
            if (!$clase) {
                return response()->json([
                    'status' => 'ok'
                ], 200);
            }
            $clase->delete();
            return response()->json([
                'status' => 'ok'
            ], 200);
        } catch (\Exception $e) {
            response()->json([
                'status' => 404,
                'message' => $e->getMessage()
            ], 404)->send();
            die();
        }
    }
}
