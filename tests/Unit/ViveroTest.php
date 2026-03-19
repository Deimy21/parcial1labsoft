<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Vivero;
use App\Models\Finca;
use App\Models\Productor;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViveroTest extends TestCase
{
    use RefreshDatabase;

    /**
     * PRUEBA 1: Verificar creación de vivero con datos reales del sistema
     * 
     * 🎯 OBJETIVO:
     * Validar que podemos crear un vivero usando la misma estructura de datos
     * que se maneja en el sistema (con código, tipo de cultivo y finca asociada).
     * Crea una instancia de Vivero sin guardar en BD.
     * 
     * 📊 DATOS DEL SISTEMA (basado en los controladores y la interfaz):
     * - Código: Identificador único del vivero dentro de una finca (VIV-rb075, VIV-zo328, VIV-bf953)
     * - Tipo de cultivo: Helecho, Orquídea, Pimiento, Café, etc
     * - finca_id: Relación con la finca donde se ubica el vivero
     */
    public function test_puede_crear_vivero_como_en_el_sistema()
    {
        // Crear un productor
        $productor = Productor::factory()->create([
            'nombre' => 'Carlos',
            'apellido' => 'López',
            'correo' => 'carlos.lopez@email.com'
        ]);

        // Crear una finca similar a las que aparecen en la interfaz
        $finca = Finca::factory()->create([
            'numero_catastro' => 'CATA-789-012',
            'municipio' => 'Rionegro',
            'productor_id' => $productor->id
        ]);

        // Crear vivero con datos similares a la interfaz
        $vivero = new Vivero([
            'codigo' => 'VIV-test-789', // Código similar a los mostrados (VIV-rb075, VIV-zo328)
            'tipo_cultivo' => 'Café', // Tipo de cultivo como en la interfaz
            'finca_id' => $finca->id // Relación con la finca
        ]);

        $this->assertInstanceOf(Vivero::class, $vivero);
        $this->assertEquals('VIV-test-789', $vivero->codigo);
        $this->assertEquals('Café', $vivero->tipo_cultivo);
        $this->assertEquals($finca->id, $vivero->finca_id);
        
        // Verificar formato del código (similar a los de la interfaz)
        $this->assertStringStartsWith('VIV-', $vivero->codigo);
        $this->assertEquals(12, strlen($vivero->codigo)); // VIV-test-789 = 12 caracteres
    }

    /**
     * PRUEBA 2: Verificar campos fillable basados en el formulario de creación
     * 
     * 🎯 OBJETIVO:
     * Confirmar que los campos fillable corresponden exactamente a los campos
     * del formulario de creación/edición de viveros en la interfaz web.
     * 
     * 📋 FORMULARIO DE VIVEROS (según ViveroWebController):
     * - Código (input text, required, unique por finca)
     * - Tipo de cultivo (input text, required)
     * - Finca (select con fincas, required)
     */
    public function test_campos_fillable_coinciden_con_formulario()
    {
        $vivero = new Vivero();
        
        $fillable = $vivero->getFillable();
        
        // Estos son los campos que aparecen en el formulario de la interfaz
        $camposDelFormulario = [
            'codigo',        // Campo de código en la interfaz
            'tipo_cultivo',  // Campo de tipo de cultivo
            'finca_id'       // Select de fincas
        ];
        
        foreach ($camposDelFormulario as $campo) {
            $this->assertContains($campo, $fillable, 
                "El campo '$campo' debería estar en fillable porque aparece en el formulario");
        }
        
        $this->assertCount(3, $fillable, "Debe haber exactamente 3 campos fillable como en el formulario");
        
        // Verificar que campos protegidos NO están en fillable
        $this->assertNotContains('id', $fillable);
        $this->assertNotContains('created_at', $fillable);
        $this->assertNotContains('updated_at', $fillable);
    }

    /**
     * PRUEBA 3: Verificar formato y validaciones de campos específicos
     * 
     * 🎯 OBJETIVO:
     * Validar que los campos del vivero tienen el formato esperado
     * y cumplen con las reglas de validación del sistema.
     * 
     * 📋 VALIDACIONES EN CONTROLADORES:
     * - API: código único dentro de la misma finca
     * - Web: código único por finca con mensaje personalizado
     * - Ambos: tipo_cultivo string con máximo de caracteres
     */
    public function test_campos_tienen_formato_esperado()
    {
        // Probar creación con diferentes formatos de código
        $vivero1 = new Vivero([
            'codigo' => 'VIV-abc-123', // Formato con letras y números
            'tipo_cultivo' => 'Helecho',
            'finca_id' => 1
        ]);

        $vivero2 = new Vivero([
            'codigo' => 'INV-987-xz', // Formato con prefijo diferente
            'tipo_cultivo' => 'Orquídea',
            'finca_id' => 2
        ]);

        $vivero3 = new Vivero([
            'codigo' => 'VIV-001', // Formato simple
            'tipo_cultivo' => 'Pimiento',
            'finca_id' => 3
        ]);

        // Verificar que diferentes formatos de código son aceptados
        $this->assertEquals('VIV-abc-123', $vivero1->codigo);
        $this->assertEquals('INV-987-xz', $vivero2->codigo);
        $this->assertEquals('VIV-001', $vivero3->codigo);
        
        // Verificar diferentes tipos de cultivo (como en la interfaz)
        $this->assertEquals('Helecho', $vivero1->tipo_cultivo);
        $this->assertEquals('Orquídea', $vivero2->tipo_cultivo);
        $this->assertEquals('Pimiento', $vivero3->tipo_cultivo);
        
        // Verificar que los códigos mantienen su formato original
        $this->assertStringContainsString('VIV', $vivero1->codigo);
        $this->assertStringContainsString('INV', $vivero2->codigo);
    }

    /**
     * PRUEBA 4: Verificar que la tabla 'viveros' contiene los campos necesarios
     * 
     * 🎯 OBJETIVO:
     * Confirmar que la estructura de la tabla permite almacenar todos los
     * campos que se muestran en las vistas y se usan en los controladores.
     * 
     * 📋 COLUMNAS EN LA BASE DE DATOS (según el modelo y la interfaz):
     * - id (clave primaria)
     * - codigo (único por finca)
     * - tipo_cultivo
     * - finca_id (clave foránea)
     * - created_at, updated_at (timestamps)
     * 
     * 🔗 RELACIONES:
     * - belongsTo: finca
     * - hasMany: labores
     * 
     * 📊 DATOS DE INTERFAZ:
     * En la lista de labores se muestra:
     * - VIV-rb075 Helecho (código + tipo_cultivo)
     * - VIV-zo328 Orquídea
     * - VIV-bf953 Pimiento
     */
    public function test_tabla_viveros_contiene_campos_necesarios()
    {
        $vivero = new Vivero();
        
        // Verificar nombre de tabla
        $this->assertEquals('viveros', $vivero->getTable());
        
        // Verificar que los campos principales existen en la tabla
        $this->assertTrue($vivero->getConnection()->getSchemaBuilder()->hasColumn('viveros', 'id'));
        $this->assertTrue($vivero->getConnection()->getSchemaBuilder()->hasColumn('viveros', 'codigo'));
        $this->assertTrue($vivero->getConnection()->getSchemaBuilder()->hasColumn('viveros', 'tipo_cultivo'));
        $this->assertTrue($vivero->getConnection()->getSchemaBuilder()->hasColumn('viveros', 'finca_id'));
        $this->assertTrue($vivero->getConnection()->getSchemaBuilder()->hasColumn('viveros', 'created_at'));
        $this->assertTrue($vivero->getConnection()->getSchemaBuilder()->hasColumn('viveros', 'updated_at'));
        
        // Verificar lista de columnas
        $columns = $vivero->getConnection()->getSchemaBuilder()->getColumnListing('viveros');
        $columnasEsperadas = [
            'id', 'codigo', 'tipo_cultivo', 'finca_id', 'created_at', 'updated_at'
        ];
        
        foreach ($columnasEsperadas as $columna) {
            $this->assertContains($columna, $columns, "La columna '$columna' debería existir en la tabla viveros");
        }
        
        // Verificar que la tabla tiene la clave foránea necesaria
        $this->assertTrue($vivero->getConnection()->getSchemaBuilder()->hasColumn('viveros', 'finca_id'));
    }

    /**
     * PRUEBA ADICIONAL (OPCIONAL): Verificar el formato de visualización en interfaz
     * 
     * Esta prueba verifica que podemos generar el formato de visualización
     * que aparece en la interfaz: "VIV-rb075 Helecho" (código + tipo_cultivo)
     */
    public function test_puede_generar_formato_de_visualizacion_como_en_interfaz()
    {
        $vivero = new Vivero([
            'codigo' => 'VIV-rb075',
            'tipo_cultivo' => 'Helecho'
        ]);
        
        // Formato mostrado en la interfaz: "VIV-rb075 Helecho"
        $visualizacionInterfaz = $vivero->codigo . ' ' . $vivero->tipo_cultivo;
        
        $this->assertEquals('VIV-rb075 Helecho', $visualizacionInterfaz);
        $this->assertStringContainsString($vivero->codigo, $visualizacionInterfaz);
        $this->assertStringContainsString($vivero->tipo_cultivo, $visualizacionInterfaz);
    }
}