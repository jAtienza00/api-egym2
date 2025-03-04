<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\MiController;

class UsuarioPy extends MiController
{
    private static $url = "http://localhost:5000/usuarios";

    /**
     * Obtener todos los usuarios.
     * @OA\Get(
     *     path="/api/usuarios",
     *     summary="Devuelve la lista de usuarios",
     *     @OA\Response(response=200, description="Lista de usuarios")
     * )
     */
    public function index(Request $request)
    {
        if (isset($request->nombre) && isset($request->contrasenia)) {
            return $this->buscarUsu($request);
        }
        $ch = curl_init(UsuarioPy::$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($ch));
        return response()->json($response, 200);
    }

    /**
     * Obtener un usuario por ID.
     * @OA\Get(
     *     path="/api/usuarios/{id}",
     *     summary="Devuelve un usuario específico",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Datos del usuario")
     * )
     */
    public function show($id)
    {
        $ch = curl_init(UsuarioPy::$url . '/' .  $id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($ch));
        return response()->json($response, 200);
    }

    /**
     * Crear un nuevo usuario.
     * @OA\Post(
     *     path="/api/usuarios",
     *     summary="Crea un usuario",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "contrasenia"},
     *             @OA\Property(property="nombre", type="string"),
     *             @OA\Property(property="contrasenia", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Usuario creado")
     * )
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string',
                'contrasenia' => 'required|string'
            ]);
            $ch = curl_init(UsuarioPy::$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($validated));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
            $response = json_decode(curl_exec($ch));
            curl_close($ch);
            return response()->json($response, 201);
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 422);
        }
    }

    /**
     * Actualizar un usuario.
     * @OA\Put(
     *     path="/api/usuarios/{id}",
     *     summary="Actualiza un usuario",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "contrasenia"},
     *             @OA\Property(property="nombre", type="string"),
     *             @OA\Property(property="contrasenia", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Usuario actualizado")
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string',
                'contrasenia' => 'required|string'
            ]);
            $ch = curl_init(UsuarioPy::$url . '/' .  $id);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($validated));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
            $response = json_decode(curl_exec($ch));
            curl_close($ch);
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'No se ha encontrado un usuario con ese id'], 404);
        }
    }

    /**
     * Eliminar un usuario.
     * @OA\Delete(
     *     path="/api/usuarios/{id}",
     *     summary="Elimina un usuario",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Usuario eliminado")
     * )
     */
    public function destroy($id)
    {
        try {
            $ch = curl_init(UsuarioPy::$url . '/' .  $id);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
            $response = json_decode(curl_exec($ch));
            curl_close($ch);
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    private function buscarUsu(Request $request)
    {
        $nombre = $request->nombre;
        $contrasenia = $request->contrasenia;
        $ch = curl_init(UsuarioPy::$url. '?nombre='. $nombre . '&contrasenia=' . $contrasenia);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($ch));
        return response()->json($response, 200);
    }
}
