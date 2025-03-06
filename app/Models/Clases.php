<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Clases",
 *     required={"nombre", "hora", "sala", "imagen"},
 *     @OA\Property(property="id", type="integer", example=1, description="ID único de la clase"),
 *     @OA\Property(property="nombre", type="string", example="Yoga", description="Nombre de la clase"),
 *     @OA\Property(property="hora", type="string", format="time", example="10:00", description="Hora de la clase"),
 *     @OA\Property(property="sala", type="string", example="Sala A", description="Sala donde se imparte la clase"),
 *     @OA\Property(property="imagen", type="string", example="https://ejemplo.com/imagen.jpg", description="URL de la imagen representativa"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-02-26T15:00:00Z", description="Fecha de creación"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-02-27T10:30:00Z", description="Fecha de última actualización")
 * )
 */
class Clases extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'hora',
        'sala',
        'imagen'
    ];
}
