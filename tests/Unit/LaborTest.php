<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Labor;
use App\Models\Vivero;
use App\Models\ProductoControl;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LaborTest extends TestCase
{
    use RefreshDatabase;

    /**
     * PRUEBA 1: Verificar creación de labor con datos reales del sistema
     * 
     * 🎯 OBJETIVO:
     * Validar que podemos crear una labor usando la misma estructura de datos
     * que se muestra en la interfaz (con vivero, producto control y fechas reales).
     * Crea una instancia de Labor sin guardar en BD
     * 
     * 📊 DATOS DEL SISTEMA (basado en la interfaz):
     * - Viveros: VIV-rb075 (Helecho), VIV-zo328 (Orquídea), VIV-bf953 (Pimiento)
     * - Productos: Hongo, Plaga, Fertilizante
     * - Fechas: 16/03/2026, 21/02/2026, 23/01/2026
     */
    public function test_puede_crear_labor_como_en_la_interfaz()
    {
        // Crear un vivero similar a los que aparecen en la interfaz
        $vivero = Vivero::factory()->create([
            'codigo' => 'VIV-test-001',
            'tipo_cultivo' => 'Café'
        ]);

        // Crear un producto de control como los que se ven en la lista
        $producto = ProductoControl::factory()->create([
            'nombre_producto' => 'Fungicida Premium',
            'tipo' => 'hongo'
        ]);

        // Crear labor con datos similares a la interfaz
        $labor = new Labor([
            'fecha' => '2026-03-16', // Fecha similar a "16/03/2026" de la interfaz
            'descripcion' => 'Aplicación de fungicida en cultivo de café', // Similar a las descripciones mostradas
            'vivero_id' => $vivero->id,
            'producto_control_id' => $producto->id
        ]);

        $this->assertInstanceOf(Labor::class, $labor);
        $this->assertEquals('2026-03-16', $labor->fecha->format('Y-m-d'));
        $this->assertStringContainsString('fungicida', strtolower($labor->descripcion));
        $this->assertEquals($vivero->id, $labor->vivero_id);
        $this->assertEquals($producto->id, $labor->producto_control_id);
    }

    /**
     * PRUEBA 2: Verificar campos fillable basados en el formulario de creación
     * 
     * 🎯 OBJETIVO:
     * Confirmar que los campos fillable corresponden exactamente a los campos
     * del formulario de creación/edición de labores en la interfaz.
     * 
     * 📋 FORMULARIO DE LABORES (según la interfaz):
     * - Fecha (input date)
     * - Vivero (select con viveros)
     * - Producto Control (select con productos)
     * - Descripción (textarea)
     */
    public function test_campos_fillable_coinciden_con_formulario()
    {
        $labor = new Labor();
        
        $fillable = $labor->getFillable();
        
        // Estos son los campos que aparecen en el formulario de la interfaz
        $camposDelFormulario = [
            'fecha',           // Campo de fecha en la interfaz
            'descripcion',     // Textarea para descripción
            'vivero_id',       // Select de viveros
            'producto_control_id' // Select de productos de control
        ];
        
        foreach ($camposDelFormulario as $campo) {
            $this->assertContains($campo, $fillable, 
                "El campo '$campo' debería estar en fillable porque aparece en el formulario");
        }
        
        $this->assertCount(4, $fillable, "Debe haber exactamente 4 campos fillable como en el formulario");
    }

    /**
     * PRUEBA 3: Verificar el formato de fechas como se muestra en la interfaz
     * 
     * 🎯 OBJETIVO:
     * Validar que las fechas pueden ser formateadas de la misma manera
     * que se muestran en la interfaz (ej: "16/03/2026" y "1 day ago").
     * 
     * 📅 FORMATOS EN INTERFAZ:
     * - Fecha principal: 16/03/2026
     * - Texto relativo: "1 day ago", "3 weeks ago", "2 months ago"
     */
    public function test_fechas_pueden_formatearse_como_en_interfaz()
    {
        // Crear labor con fecha de hoy (simulando "1 day ago")
        $laborHoy = new Labor([
            'fecha' => now()->subDay() // Ayer
        ]);

        // Crear labor con fecha de hace 3 semanas (como en la interfaz)
        $laborTresSemanas = new Labor([
            'fecha' => now()->subWeeks(3)
        ]);

        // Crear labor con fecha de hace 2 meses (como en la interfaz)
        $laborDosMeses = new Labor([
            'fecha' => now()->subMonths(2)
        ]);

        // Verificar formato de fecha principal (d/m/Y)
        $this->assertEquals(
            now()->subDay()->format('d/m/Y'),
            $laborHoy->fecha->format('d/m/Y')
        );

        // Verificar que podemos generar textos como "1 day ago", "3 weeks ago"
        $this->assertStringContainsString('day', $laborHoy->fecha->diffForHumans());
        $this->assertStringContainsString('week', $laborTresSemanas->fecha->diffForHumans());
        $this->assertStringContainsString('month', $laborDosMeses->fecha->diffForHumans());
        
        // Verificar formato específico usado en la interfaz
        $this->assertEquals(
            now()->subMonths(2)->format('d/m/Y'),
            $laborDosMeses->fecha->format('d/m/Y')
        );
    }

    /**
     * PRUEBA 4: Verificar que la tabla 'labores' contiene los registros mostrados
     * 
     * 🎯 OBJETIVO:
     * Confirmar que la estructura de la tabla permite almacenar todos los
     * campos que se muestran en la lista de labores de la interfaz.
     * 
     * 📋 COLUMNAS MOSTRADAS EN INTERFAZ:
     * - FECHA
     * - VIVERO (código y tipo)
     * - FINCA / MUNICIPIO
     * - DESCRIPCIÓN
     * - PRODUCTO CONTROL
     * - ACCIONES
     */
    public function test_tabla_labores_contiene_campos_necesarios()
    {
        $labor = new Labor();
        
        // Verificar nombre de tabla
        $this->assertEquals('labores', $labor->getTable());
        
        // Verificar que los campos de la tabla existen en el modelo
        $this->assertTrue($labor->getConnection()->getSchemaBuilder()->hasColumn('labores', 'fecha'));
        $this->assertTrue($labor->getConnection()->getSchemaBuilder()->hasColumn('labores', 'descripcion'));
        $this->assertTrue($labor->getConnection()->getSchemaBuilder()->hasColumn('labores', 'vivero_id'));
        $this->assertTrue($labor->getConnection()->getSchemaBuilder()->hasColumn('labores', 'producto_control_id'));
        
        // Verificar que la tabla tiene las claves foráneas necesarias
        $this->assertTrue($labor->getConnection()->getSchemaBuilder()->hasColumn('labores', 'vivero_id'));
        $this->assertTrue($labor->getConnection()->getSchemaBuilder()->hasColumn('labores', 'producto_control_id'));
    }
}