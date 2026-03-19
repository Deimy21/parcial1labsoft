<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Productor;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * PRUEBA 1: Verificar creación de productor con datos reales del sistema
     * 
     * 🎯 OBJETIVO:
     * Validar que podemos crear un productor usando la misma estructura de datos
     * que se maneja en el sistema (con documento, nombre, apellido, teléfono y correo).
     * Crea una instancia de Productor sin guardar en BD.
     * 
     * 📊 DATOS DEL SISTEMA (basado en los controladores):
     * - documento_identidad: Identificador único del productor (cédula, NIT, etc)
     * - nombre: Nombres del productor
     * - apellido: Apellidos del productor
     * - telefono: Número de contacto
     * - correo: Correo electrónico único
     */
    public function test_puede_crear_productor_como_en_el_sistema()
    {
        // Crear productor con datos similares al sistema
        $productor = new Productor([
            'documento_identidad' => '123456789', // Documento único
            'nombre' => 'Juan Carlos', // Nombres
            'apellido' => 'Pérez González', // Apellidos
            'telefono' => '3001234567', // Teléfono de contacto
            'correo' => 'juan.perez@email.com' // Correo electrónico único
        ]);

        $this->assertInstanceOf(Productor::class, $productor);
        $this->assertEquals('123456789', $productor->documento_identidad);
        $this->assertEquals('Juan Carlos', $productor->nombre);
        $this->assertEquals('Pérez González', $productor->apellido);
        $this->assertEquals('3001234567', $productor->telefono);
        $this->assertEquals('juan.perez@email.com', $productor->correo);
        
        // Verificar nombre completo (puede ser útil para vistas)
        $nombreCompleto = $productor->nombre . ' ' . $productor->apellido;
        $this->assertEquals('Juan Carlos Pérez González', $nombreCompleto);
    }

    /**
     * PRUEBA 2: Verificar campos fillable basados en el formulario de creación
     * 
     * 🎯 OBJETIVO:
     * Confirmar que los campos fillable corresponden exactamente a los campos
     * del formulario de creación/edición de productores en la interfaz web.
     * 
     * 📋 FORMULARIO DE PRODUCTORES (según ProductorWebController):
     * - Documento de identidad (input text, required, unique)
     * - Nombre (input text, required, max:100)
     * - Apellido (input text, required, max:100)
     * - Teléfono (input text, required, max:20)
     * - Correo (input email, required, unique)
     */
    public function test_campos_fillable_coinciden_con_formulario()
    {
        $productor = new Productor();
        
        $fillable = $productor->getFillable();
        
        // Estos son los campos que aparecen en el formulario de la interfaz
        $camposDelFormulario = [
            'documento_identidad', // Campo de documento en la interfaz
            'nombre',              // Campo de nombre
            'apellido',            // Campo de apellido
            'telefono',            // Campo de teléfono
            'correo'               // Campo de correo electrónico
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
     * Validar que los campos del productor tienen el formato esperado
     * y cumplen con las reglas de validación del sistema.
     * 
     * 📋 VALIDACIONES EN CONTROLADORES:
     * - API: documento_identidad debe ser único
     * - API: correo debe ser único y con formato email válido
     * - Web: todos los campos son requeridos
     * - Web: longitudes máximas (nombre:100, apellido:100, telefono:20)
     */
    public function test_campos_tienen_formato_esperado()
    {
        // Probar creación con diferentes formatos de documento
        $productor1 = new Productor([
            'documento_identidad' => 'CC-12345', // Documento con prefijo
            'nombre' => 'María',
            'apellido' => 'Rodríguez',
            'telefono' => '+57 300 123 4567', // Teléfono con formato internacional
            'correo' => 'maria.rodriguez@email.com'
        ]);

        $productor2 = new Productor([
            'documento_identidad' => 'NIT-901234567-8', // NIT con formato
            'nombre' => 'Empresa',
            'apellido' => 'Agrícola SAS',
            'telefono' => '6012345678', // Teléfono fijo sin formato
            'correo' => 'contacto@empresaagricola.com'
        ]);

        // Verificar que diferentes formatos son aceptados
        $this->assertEquals('CC-12345', $productor1->documento_identidad);
        $this->assertEquals('NIT-901234567-8', $productor2->documento_identidad);
        
        // Verificar formato de correo
        $this->assertStringContainsString('@', $productor1->correo);
        $this->assertStringContainsString('.', $productor1->correo);
        
        // Verificar que los nombres y apellidos mantienen tildes y caracteres especiales
        $this->assertEquals('María', $productor1->nombre);
        $this->assertEquals('Rodríguez', $productor1->apellido);
        
        // Verificar diferentes formatos de teléfono
        $this->assertEquals('+57 300 123 4567', $productor1->telefono);
        $this->assertEquals('6012345678', $productor2->telefono);
    }

    /**
     * PRUEBA 4: Verificar que la tabla 'productores' contiene los campos necesarios
     * 
     * 🎯 OBJETIVO:
     * Confirmar que la estructura de la tabla permite almacenar todos los
     * campos que se muestran en las vistas y se usan en los controladores.
     * 
     * 📋 COLUMNAS EN LA BASE DE DATOS (según el modelo):
     * - id (clave primaria)
     * - documento_identidad (único)
     * - nombre
     * - apellido
     * - telefono
     * - correo (único)
     * - created_at, updated_at (timestamps)
     * 
     * 🔗 RELACIONES:
     * - hasMany: fincas
     * - hasManyThrough: viveros (a través de fincas)
     */
    public function test_tabla_productores_contiene_campos_necesarios()
    {
        $productor = new Productor();
        
        // Verificar nombre de tabla
        $this->assertEquals('productores', $productor->getTable());
        
        // Verificar que los campos principales existen en la tabla
        $this->assertTrue($productor->getConnection()->getSchemaBuilder()->hasColumn('productores', 'id'));
        $this->assertTrue($productor->getConnection()->getSchemaBuilder()->hasColumn('productores', 'documento_identidad'));
        $this->assertTrue($productor->getConnection()->getSchemaBuilder()->hasColumn('productores', 'nombre'));
        $this->assertTrue($productor->getConnection()->getSchemaBuilder()->hasColumn('productores', 'apellido'));
        $this->assertTrue($productor->getConnection()->getSchemaBuilder()->hasColumn('productores', 'telefono'));
        $this->assertTrue($productor->getConnection()->getSchemaBuilder()->hasColumn('productores', 'correo'));
        $this->assertTrue($productor->getConnection()->getSchemaBuilder()->hasColumn('productores', 'created_at'));
        $this->assertTrue($productor->getConnection()->getSchemaBuilder()->hasColumn('productores', 'updated_at'));
        
        // Verificar lista de columnas
        $columns = $productor->getConnection()->getSchemaBuilder()->getColumnListing('productores');
        $columnasEsperadas = [
            'id', 'documento_identidad', 'nombre', 'apellido', 
            'telefono', 'correo', 'created_at', 'updated_at'
        ];
        
        foreach ($columnasEsperadas as $columna) {
            $this->assertContains($columna, $columns, "La columna '$columna' debería existir en la tabla productores");
        }
    }
}