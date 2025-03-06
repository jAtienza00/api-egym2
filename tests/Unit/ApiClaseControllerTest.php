<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Clases;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

class ApiClaseControllerTest extends TestCase
{
    use RefreshDatabase;

    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();

        // Ejecuta las migraciones antes de cada test
        Artisan::call('migrate:fresh'); // Usa migrate:fresh para limpiar la base de datos y aplicar todas las migraciones
    }
    /*
    Metodo index
    */

    /** @test */
    public function it_can_get_all_classes()
    {

        // Realizar la solicitud GET
        $response = $this->getJson('/api/clases');

        // Asegurarse de que la respuesta sea exitosa
        $response->assertStatus(200);

        // Verificar que la respuesta contenga una lista de clases
        $response->assertJsonCount(Clases::count());
    }

    /** @test */
    public function it_can_paginate_classes()
    {
        $count = Clases::count() / 2;
        // Realizar la solicitud GET con paginación
        $response = $this->getJson('/api/clases?paginar=' . $count);

        // Asegurarse de que la respuesta sea exitosa
        $response->assertStatus(200);

        // Verificar que la respuesta esté paginada
        $data = $response->json("data");
        return $this->comparar($data, $count);
    }
    private function comparar($data, $count)
    {
        return count($data) === $count;
    }
    /** @test */
    public function it_can_filter_classes_by_sala()
    {
        $count = Clases::where('sala', 1)->count();
        // Realizar la solicitud GET con el parámetro sala
        $response = $this->getJson('/api/clases?sala=1');

        // Asegurarse de que la respuesta sea exitosa
        $response->assertStatus(200);

        // Verificar que solo las clases de la sala 1 sean retornadas
        $response->assertJsonCount($count);
    }

    /*
    Metodo show
    */
    /** @test */
    public function it_can_show_class_details()
    {
        // Obtener la primera clase
        $clase = Clases::create([
            'nombre' => 'Clase',
            'hora' => '13:00:00',
            'sala' => 2,
            'imagen' => 'url_actualizada',
        ]);

        // Realizar la solicitud GET para obtener la clase por ID
        $response = $this->getJson("/api/clases/{$clase->id}");

        // Asegurarse de que la respuesta sea exitosa
        $response->assertStatus(200);

        // Verificar que los datos de la clase sean los correctos
        $response->assertJsonFragment([
            'nombre' => $clase->nombre,
            'hora' => $clase->hora,
        ]);
    }

    /** @test */
    public function it_returns_404_if_class_not_found()
    {
        // Intentar obtener una clase que no existe
        $response = $this->getJson('/api/clases/999');

        // Asegurarse de que la respuesta sea un error 404
        $response->assertStatus(404);
    }
    /** @test */
    public function it_can_update_an_existing_class()
    {
        // Crear una clase en la base de datos
        $clase = Clases::create([
            'nombre' => 'Clase',
            'hora' => '13:00',
            'sala' => 2,
            'imagen' => 'url_actualizada',
        ]);

        // Datos para actualizar la clase
        $data = [
            'nombre' => 'Clase de Pilates',
            'hora' => '16:00',
            'sala' => 2,
            'imagen' => 'url_actualizada',
        ];

        // Realizar la solicitud PUT
        $response = $this->putJson("/api/clases/{$clase->id}", $data);

        // Asegurarse de que la respuesta sea exitosa
        $response->assertStatus(200);

        // Verificar que la clase haya sido actualizada
        $this->assertDatabaseHas('clases', [
            'id' => $clase->id,
            'nombre' => 'Clase de Pilates',
            'hora' => '16:00',
        ]);
    }

    /** @test */
    public function it_returns_404_if_class_to_update_not_found()
    {
        // Intentar actualizar una clase que no existe
        $data = [
            'nombre' => 'Clase de Pilates',
            'hora' => '16:00',
            'sala' => 2,
            'imagen' => 'url_actualizada',
        ];

        // Realizar la solicitud PUT
        $response = $this->putJson('/api/clases/999', $data);

        // Asegurarse de que la respuesta sea un error 404
        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_delete_a_class()
    {
        // Crear una clase en la base de datos
        $clase = Clases::create([
            'nombre' => 'Clase',
            'hora' => '13:00',
            'sala' => 2,
            'imagen' => 'url_actualizada',
        ]);

        // Realizar la solicitud DELETE
        $response = $this->deleteJson("/api/clases/{$clase->id}");

        // Asegurarse de que la respuesta sea exitosa
        $response->assertStatus(200);

        // Verificar que la clase haya sido eliminada de la base de datos
        $this->assertDatabaseMissing('clases', [
            'id' => $clase->id,
        ]);
    }

    /** @test */
    public function it_returns_200_if_class_to_delete_not_found()
    {
        // Intentar eliminar una clase que no existe
        $response = $this->deleteJson('/api/clases/999');

        // Asegurarse de que la respuesta sea un error 404
        $response->assertStatus(200);
    }
}
