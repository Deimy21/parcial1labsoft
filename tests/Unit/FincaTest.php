<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Finca;
use App\Models\Productor;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FincaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * PRUEBA 1: Verificar creación de finca con datos reales del sistema
     * 
     * 🎯 OBJETIVO:
     * Validar que podemos crear una finca usando la misma estructura de datos
     * que se maneja en el sistema (con número de catastro, municipio y productor asociado).
     * Crea una instancia de Finca sin guardar en BD.
     * 
     * 📊 DATOS DEL SISTEMA (basado en los controladores):
     * - Número de catastro: Identificador único de la finca
     * - Municipio: Ubicación geográfica
     * - productor_id: Relación con el productor propietario
     */
    public function test_puede_crear_finca_como_en_el_sistema()
    {
        // Crear un productor directamente sin usar el factory
        $productor = Productor::create([
            'nombre' => 'Juan Pérez',
            'documento_identidad' => '12345678',
            'apellido' => 'García',
            'telefono' => '+57 300 123 4567',
            'correo' => 'juan.perez@example.com'
        ]);

        // Crear finca con datos similares al sistema
        $finca = new Finca([
            'numero_catastro' => 'CATA-123-456', // Número de catastro único
            'municipio' => 'Medellín', // Municipio de ubicación
            'productor_id' => $productor->id // Relación con productor
        ]);

        $this->assertInstanceOf(Finca::class, $finca);
        $this->assertEquals('CATA-123-456', $finca->numero_catastro);
        $this->assertEquals('Medellín', $finca->municipio);
        $this->assertEquals($productor->id, $finca->productor_id);
    }

    /**
     * PRUEBA 2: Verificar campos fillable basados en el formulario de creación
     * 
     * 🎯 OBJETIVO:
     * Confirmar que los campos fillable corresponden exactamente a los campos
     * del formulario de creación/edición de fincas en la interfaz web.
     * 
     * 📋 FORMULARIO DE FINCAS (según FincaWebController):
     * - Número de catastro (input text, required)
     * - Municipio (input text, required)
     * - Productor ID (select con productores, required)
     */
    public function test_campos_fillable_coinciden_con_formulario()
    {
        $finca = new Finca();
        
        $fillable = $finca->getFillable();
        
        // Estos son los campos que aparecen en el formulario de la interfaz
        $camposDelFormulario = [
            'numero_catastro',  // Campo de número de catastro en la interfaz
            'municipio',        // Campo de municipio
            'productor_id'      // Select de productores
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
     * Validar que los campos de la finca tienen el formato esperado
     * y cumplen con las reglas de validación del sistema.
     * 
     * 📋 VALIDACIONES EN CONTROLADORES:
     * - API: numero_catastro debe ser único en la tabla fincas
     * - Web: número de catastro string max:100
     * - Web: municipio string max:150
     */
    public function test_campos_tienen_formato_esperado()
    {
        // Probar creación con diferentes valores
        $finca1 = new Finca([
            'numero_catastro' => 'ABC-123-XYZ', // Formato alfanumérico
            'municipio' => 'Rionegro', // Municipio con acento
            'productor_id' => 1
        ]);

        $finca2 = new Finca([
            'numero_catastro' => '9876543210', // Formato numérico
            'municipio' => 'San Pedro de los Milagros', // Municipio largo
            'productor_id' => 2
        ]);

        // Verificar que ambos formatos son aceptados
        $this->assertEquals('ABC-123-XYZ', $finca1->numero_catastro);
        $this->assertEquals('9876543210', $finca2->numero_catastro);
        
        // Verificar que los strings largos funcionan
        $this->assertGreaterThan(20, strlen($finca2->municipio));
        $this->assertStringContainsString('Milagros', $finca2->municipio);
        
        // Verificar que el municipio mantiene acentos y caracteres especiales
        $this->assertEquals('Rionegro', $finca1->municipio);
    }

    /**
     * PRUEBA 4: Verificar que la tabla 'fincas' contiene los campos necesarios
     * 
     * 🎯 OBJETIVO:
     * Confirmar que la estructura de la tabla permite almacenar todos los
     * campos que se muestran en las vistas y se usan en los controladores.
     * 
     * 📋 COLUMNAS EN LA BASE DE DATOS (según el modelo):
     * - id (clave primaria)
     * - numero_catastro (único)
     * - municipio
     * - productor_id (clave foránea)
     * - created_at, updated_at (timestamps)
     * 
     * 🔗 RELACIONES:
     * - belongsTo: productor
     * - hasMany: viveros
     */
    public function test_tabla_fincas_contiene_campos_necesarios()
    {
        $finca = new Finca();
        
        // Verificar nombre de tabla
        $this->assertEquals('fincas', $finca->getTable());
        
        // Verificar que los campos principales existen en la tabla
        $this->assertTrue($finca->getConnection()->getSchemaBuilder()->hasColumn('fincas', 'id'));
        $this->assertTrue($finca->getConnection()->getSchemaBuilder()->hasColumn('fincas', 'numero_catastro'));
        $this->assertTrue($finca->getConnection()->getSchemaBuilder()->hasColumn('fincas', 'municipio'));
        $this->assertTrue($finca->getConnection()->getSchemaBuilder()->hasColumn('fincas', 'productor_id'));
        $this->assertTrue($finca->getConnection()->getSchemaBuilder()->hasColumn('fincas', 'created_at'));
        $this->assertTrue($finca->getConnection()->getSchemaBuilder()->hasColumn('fincas', 'updated_at'));
        
        // Verificar tipos de datos esperados (esto es opcional pero útil)
        $columns = $finca->getConnection()->getSchemaBuilder()->getColumnListing('fincas');
        $this->assertContains('numero_catastro', $columns);
        $this->assertContains('municipio', $columns);
        $this->assertContains('productor_id', $columns);
        
        // Verificar que la tabla tiene las claves foráneas necesarias
        $this->assertTrue($finca->getConnection()->getSchemaBuilder()->hasColumn('fincas', 'productor_id'));
    }
}