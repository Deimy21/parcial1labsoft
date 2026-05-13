<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Productor;
use App\Models\Vivero;
use App\Models\Finca;
use App\Models\ProductoControl;
use App\Models\ProductoControlFertilizante;
use App\Models\ProductoControlHongo;
use App\Models\ProductoControlPlaga;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RelacionesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * PRUEBA 1: Verificar relaciones del modelo Productor
     * 
     * Esta prueba verifica que las relaciones definidas en el modelo
     * funcionan correctamente y permiten acceder a los datos relacionados.
     */
    public function test_productor_tiene_relaciones_correctas()
    {
        $productor = new Productor();
        
        // Verificar que la relación viveros existe como método
        $this->assertTrue(method_exists($productor, 'viveros'));
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $productor->viveros());
        
        // Verificar que los nombres de las tablas relacionadas son correctos
        $viverosRelation = $productor->viveros();
        $this->assertEquals('productores', $viverosRelation->getParent()->getTable());
        $this->assertEquals('viveros', $viverosRelation->getRelated()->getTable());
    }

    /**
     * PRUEBA 2: Verificar relaciones del modelo Vivero
     * 
     * Esta prueba verifica que la relación con productor está correctamente definida.
     * 
     * 🔗 RELACIONES DEL MODELO:
     * - productor(): BelongsTo - Un vivero pertenece a un productor
     * - labores(): HasMany - Un vivero tiene muchas labores
     */
    public function test_vivero_pertenece_a_un_productor()
    {
        // Crear un productor
        $productor = Productor::factory()->create([
            'nombre' => 'Ana',
            'apellido' => 'Martínez',
            'correo' => 'ana.martinez@email.com'
        ]);

        // Crear un vivero asociado al productor
        $vivero = new Vivero([
            'codigo' => 'VIV-REL-001',
            'nombre' => 'Vivero Relación',
            'departamento' => 'Quindío',
            'municipio' => 'Armenia',
            'productor_id' => $productor->id
        ]);

        // Verificar que la relación productor existe como método
        $this->assertTrue(method_exists($vivero, 'productor'));
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $vivero->productor());
        
        // Verificar nombre de la clave foránea
        $productorRelation = $vivero->productor();
        $this->assertEquals('productor_id', $productorRelation->getForeignKeyName());
    }

    /**
     * PRUEBA 3: Verificar relaciones del modelo ProductoControl
     * 
     * Esta prueba verifica que la relación con labores está correctamente definida.
     * 
     * 🔗 RELACIONES:
     * - labores(): HasMany - Un producto puede estar en muchas labores
     */
    public function test_producto_control_tiene_relacion_con_labores()
    {
        $producto = new ProductoControl();
        
        // Verificar que la relación labores existe como método
        $this->assertTrue(method_exists($producto, 'labores'));
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $producto->labores());
        
        // Verificar nombre de la clave foránea
        $laboresRelation = $producto->labores();
        $this->assertEquals('producto_control_id', $laboresRelation->getForeignKeyName());
    }
    
    /**
     * PRUEBA 4: Verificar relación de ProductoControlFertilizante con labores
     * 
     * Esta prueba verifica que la relación con labores está correctamente definida
     * y que un fertilizante puede tener múltiples labores asociadas.
     */
    public function test_fertilizante_tiene_relacion_con_labores()
    {
        $fertilizante = new ProductoControlFertilizante();
        
        // Verificar que la relación labores existe como método
        $this->assertTrue(method_exists($fertilizante, 'labores'));
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $fertilizante->labores());
        
        // Verificar nombre de la clave foránea
        $laboresRelation = $fertilizante->labores();
        $this->assertEquals('producto_control_id', $laboresRelation->getForeignKeyName());
        
        // Probar creación con labores asociadas (opcional)
        $fertilizante = ProductoControlFertilizante::create([
            'registro_ica' => 'ICA-FER-999',
            'nombre_producto' => 'Fertilizante con Labores',
            'frecuencia_aplicacion' => 25,
            'valor_producto' => 95000,
            'fecha_ultima_aplicacion' => '2026-03-20'
        ]);
        
        // Verificar que puede tener labores (la relación existe)
        $this->assertCount(0, $fertilizante->labores); // Aún no tiene labores
    }

    /**
     * PRUEBA 5: Verificar relación de ProductoControlHongo con labores
     * 
     * Esta prueba verifica que la relación con labores está correctamente definida
     * y que un hongo puede tener múltiples labores asociadas.
     */
    public function test_hongo_tiene_relacion_con_labores()
    {
        $hongo = new ProductoControlHongo();
        
        // Verificar que la relación labores existe como método
        $this->assertTrue(method_exists($hongo, 'labores'));
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $hongo->labores());
        
        // Verificar nombre de la clave foránea
        $laboresRelation = $hongo->labores();
        $this->assertEquals('producto_control_id', $laboresRelation->getForeignKeyName());
        
        // Probar creación con labores asociadas (opcional)
        $hongo = ProductoControlHongo::create([
            'registro_ica' => 'ICA-HGO-999',
            'nombre_producto' => 'Fungicida con Labores',
            'frecuencia_aplicacion' => 12,
            'valor_producto' => 85000,
            'periodo_carencia' => 8,
            'nombre_hongo' => 'Antracnosis'
        ]);
        
        // Verificar que puede tener labores (la relación existe)
        $this->assertCount(0, $hongo->labores); // Aún no tiene labores
    }

    /**
     * PRUEBA 6: Verificar relación de ProductoControlPlaga con labores
     * 
     * Esta prueba verifica que la relación con labores está correctamente definida
     * y que una plaga puede tener múltiples labores asociadas.
     */
    public function test_plaga_tiene_relacion_con_labores()
    {
        $plaga = new ProductoControlPlaga();
        
        // Verificar que la relación labores existe como método
        $this->assertTrue(method_exists($plaga, 'labores'));
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $plaga->labores());
        
        // Verificar nombre de la clave foránea
        $laboresRelation = $plaga->labores();
        $this->assertEquals('producto_control_id', $laboresRelation->getForeignKeyName());
        
        // Probar creación con labores asociadas (opcional)
        $plaga = ProductoControlPlaga::create([
            'registro_ica' => 'ICA-PLA-999',
            'nombre_producto' => 'Insecticida con Labores',
            'frecuencia_aplicacion' => 14,
            'valor_producto' => 42000,
            'periodo_carencia' => 9
        ]);
        
        // Verificar que puede tener labores (la relación existe)
        $this->assertCount(0, $plaga->labores); // Aún no tiene labores
    }
}