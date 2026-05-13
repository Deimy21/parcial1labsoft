<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Vivero;
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
     * que se maneja en el sistema (con código, nombre, departamento, municipio y productor asociado).
     * Crea una instancia de Vivero sin guardar en BD.
     * 
     * 📊 DATOS DEL SISTEMA (basado en los controladores):
     * - codigo: Código identificador único del vivero
     * - nombre: Nombre del vivero
     * - departamento: Departamento donde se ubica
     * - municipio: Municipio donde se ubica
     * - productor_id: Relación con el productor propietario
     */
    public function test_puede_crear_vivero_como_en_el_sistema()
    {
        // Crear un productor similar a los que aparecen en el sistema
        $productor = Productor::factory()->create([
            'nombre' => 'Carlos',
            'apellido' => 'López',
            'correo' => 'carlos.lopez@email.com'
        ]);

        // Crear vivero con datos similares al sistema
        $vivero = new Vivero([
            'codigo' => 'VIV-001',           // Código único del vivero
            'nombre' => 'Vivero Central',    // Nombre del vivero
            'departamento' => 'Antioquia',   // Departamento de ubicación
            'municipio' => 'Medellín',       // Municipio de ubicación
            'productor_id' => $productor->id // Relación con productor
        ]);

        $this->assertInstanceOf(Vivero::class, $vivero);
        $this->assertEquals('VIV-001', $vivero->codigo);
        $this->assertEquals('Vivero Central', $vivero->nombre);
        $this->assertEquals('Antioquia', $vivero->departamento);
        $this->assertEquals('Medellín', $vivero->municipio);
        $this->assertEquals($productor->id, $vivero->productor_id);
    }

    /**
     * PRUEBA 2: Verificar campos fillable basados en el formulario de creación
     * 
     * 🎯 OBJETIVO:
     * Confirmar que los campos fillable corresponden exactamente a los campos
     * del formulario de creación/edición de viveros en la interfaz web.
     * 
     * 📋 FORMULARIO DE VIVEROS (según ViveroWebController):
     * - Código (input text, required, unique)
     * - Nombre (input text, required)
     * - Departamento (input text, required)
     * - Municipio (input text, required)
     * - Productor ID (select con productores, required)
     */
    public function test_campos_fillable_coinciden_con_formulario()
    {
        $vivero = new Vivero();
        
        $fillable = $vivero->getFillable();
        
        // Estos son los campos que aparecen en el formulario de la interfaz
        $camposDelFormulario = [
            'codigo',        // Campo de código en la interfaz
            'nombre',        // Campo de nombre del vivero
            'departamento',  // Campo de departamento
            'municipio',     // Campo de municipio
            'productor_id'   // Select de productores
        ];
        
        foreach ($camposDelFormulario as $campo) {
            $this->assertContains($campo, $fillable, 
                "El campo '$campo' debería estar en fillable porque aparece en el formulario");
        }
        
        $this->assertCount(5, $fillable, "Debe haber exactamente 5 campos fillable como en el formulario");
        
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
     * - Web: código único global
     * - Todos los campos son requeridos en Web
     */
    public function test_campos_tienen_formato_esperado()
    {
        // Probar creación con diferentes formatos de código y nombre
        $vivero1 = new Vivero([
            'codigo' => 'VIV-ABC-123',     // Formato con letras y números
            'nombre' => 'Vivero Norte',     // Nombre descriptivo
            'departamento' => 'Cundinamarca',
            'municipio' => 'Bogotá',
            'productor_id' => 1
        ]);

        $vivero2 = new Vivero([
            'codigo' => 'VIV-987-XZ',       // Formato con prefijo diferente
            'nombre' => 'Vivero Sur',       // Otro nombre
            'departamento' => 'Valle del Cauca',
            'municipio' => 'Cali',
            'productor_id' => 2
        ]);

        $vivero3 = new Vivero([
            'codigo' => 'VIV-001',          // Formato simple
            'nombre' => 'Vivero Oriente',
            'departamento' => 'Santander',
            'municipio' => 'Bucaramanga',
            'productor_id' => 3
        ]);

        // Verificar que diferentes formatos de código son aceptados
        $this->assertEquals('VIV-ABC-123', $vivero1->codigo);
        $this->assertEquals('VIV-987-XZ', $vivero2->codigo);
        $this->assertEquals('VIV-001', $vivero3->codigo);
        
        // Verificar nombres de viveros
        $this->assertEquals('Vivero Norte', $vivero1->nombre);
        $this->assertEquals('Vivero Sur', $vivero2->nombre);
        $this->assertEquals('Vivero Oriente', $vivero3->nombre);
        
        // Verificar departamentos y municipios
        $this->assertEquals('Cundinamarca', $vivero1->departamento);
        $this->assertEquals('Bogotá', $vivero1->municipio);
        $this->assertEquals('Valle del Cauca', $vivero2->departamento);
        $this->assertEquals('Cali', $vivero2->municipio);
        $this->assertEquals('Santander', $vivero3->departamento);
        $this->assertEquals('Bucaramanga', $vivero3->municipio);
        
        // Verificar que los códigos mantienen su formato original
        $this->assertStringContainsString('VIV', $vivero1->codigo);
        $this->assertStringContainsString('VIV', $vivero2->codigo);
        $this->assertStringContainsString('VIV', $vivero3->codigo);
    }

    /**
     * PRUEBA 4: Verificar que la tabla 'viveros' contiene los campos necesarios
     * 
     * 🎯 OBJETIVO:
     * Confirmar que la estructura de la tabla permite almacenar todos los
     * campos que se muestran en las vistas y se usan en los controladores.
     * 
     * 📋 COLUMNAS EN LA BASE DE DATOS (según el modelo):
     * - id (clave primaria)
     * - codigo (único)
     * - nombre
     * - departamento
     * - municipio
     * - productor_id (clave foránea)
     * - created_at, updated_at (timestamps)
     * 
     * 🔗 RELACIONES:
     * - belongsTo: productor
     * - hasMany: labores (opcional)
     */
    public function test_tabla_viveros_contiene_campos_necesarios()
    {
        $vivero = new Vivero();
        
        // Verificar nombre de tabla
        $this->assertEquals('viveros', $vivero->getTable());
        
        // Verificar que los campos principales existen en la tabla
        $this->assertTrue($vivero->getConnection()->getSchemaBuilder()->hasColumn('viveros', 'id'));
        $this->assertTrue($vivero->getConnection()->getSchemaBuilder()->hasColumn('viveros', 'codigo'));
        $this->assertTrue($vivero->getConnection()->getSchemaBuilder()->hasColumn('viveros', 'nombre'));
        $this->assertTrue($vivero->getConnection()->getSchemaBuilder()->hasColumn('viveros', 'departamento'));
        $this->assertTrue($vivero->getConnection()->getSchemaBuilder()->hasColumn('viveros', 'municipio'));
        $this->assertTrue($vivero->getConnection()->getSchemaBuilder()->hasColumn('viveros', 'productor_id'));
        $this->assertTrue($vivero->getConnection()->getSchemaBuilder()->hasColumn('viveros', 'created_at'));
        $this->assertTrue($vivero->getConnection()->getSchemaBuilder()->hasColumn('viveros', 'updated_at'));
        
        // Verificar lista de columnas
        $columns = $vivero->getConnection()->getSchemaBuilder()->getColumnListing('viveros');
        $columnasEsperadas = [
            'id', 'codigo', 'nombre', 'departamento', 
            'municipio', 'productor_id', 'created_at', 'updated_at'
        ];
        
        foreach ($columnasEsperadas as $columna) {
            $this->assertContains($columna, $columns, "La columna '$columna' debería existir en la tabla viveros");
        }
        
        // Verificar que la tabla tiene la clave foránea necesaria
        $this->assertTrue($vivero->getConnection()->getSchemaBuilder()->hasColumn('viveros', 'productor_id'));
    }
}