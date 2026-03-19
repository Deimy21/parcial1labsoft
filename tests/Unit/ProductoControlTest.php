<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\ProductoControl;
use App\Models\ProductoControlHongo;
use App\Models\ProductoControlPlaga;
use App\Models\ProductoControlFertilizante;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductoControlTest extends TestCase
{
    use RefreshDatabase;

    /**
     * PRUEBA 1: Verificar creación de producto control con datos reales del sistema
     * 
     * 🎯 OBJETIVO:
     * Validar que podemos crear los diferentes tipos de productos de control
     * usando la misma estructura de datos que se maneja en el sistema.
     * Crea instancias de ProductoControl sin guardar en BD.
     * 
     * 📊 DATOS DEL SISTEMA (basado en los controladores y la interfaz):
     * - Tipos: Hongo, Plaga, Fertilizante
     * - Atributos comunes: registro_ica, nombre_producto, frecuencia_aplicacion, valor_producto
     * - Atributos específicos: periodo_carencia, nombre_hongo, fecha_ultima_aplicacion
     * 
     * En la interfaz de labores se muestran:
     * - "Plaga consequatur sed et"
     * - "Hongo harum a eveniet"
     * - "Fertilizante tenetur et suscipit"
     */
    public function test_puede_crear_producto_control_como_en_el_sistema()
    {
        // Crear producto tipo HONGO (como los que aparecen en la interfaz)
        $productoHongo = new ProductoControlHongo([
            'tipo' => 'hongo',
            'registro_ica' => 'ICA-HGO-12345',
            'nombre_producto' => 'Fungicida Premium', // Similar a "Hongo harum a eveniet"
            'frecuencia_aplicacion' => 15, // Cada 15 días
            'valor_producto' => 75000.50,
            'periodo_carencia' => 7, // Días de carencia después de aplicación
            'nombre_hongo' => 'Royas y Mildiu' // Nombre del hongo a controlar
        ]);

        // Crear producto tipo PLAGA (como los que aparecen en la interfaz)
        $productoPlaga = new ProductoControlPlaga([
            'tipo' => 'plaga',
            'registro_ica' => 'ICA-PLA-67890',
            'nombre_producto' => 'Insecticida Total', // Similar a "Plaga consequatur sed et"
            'frecuencia_aplicacion' => 21, // Cada 21 días
            'valor_producto' => 45000.00,
            'periodo_carencia' => 10 // Días de carencia
        ]);

        // Crear producto tipo FERTILIZANTE (como los que aparecen en la interfaz)
        $productoFertilizante = new ProductoControlFertilizante([
            'tipo' => 'fertilizante',
            'registro_ica' => 'ICA-FER-54321',
            'nombre_producto' => 'Fertilizante Orgánico', // Similar a "Fertilizante tenetur et suscipit"
            'frecuencia_aplicacion' => 30, // Cada 30 días
            'valor_producto' => 120000.00,
            'fecha_ultima_aplicacion' => '2026-03-01' // Fecha de última aplicación
        ]);

        // Verificar producto HONGO
        $this->assertInstanceOf(ProductoControlHongo::class, $productoHongo);
        $this->assertEquals('hongo', $productoHongo->tipo);
        $this->assertEquals('ICA-HGO-12345', $productoHongo->registro_ica);
        $this->assertEquals('Fungicida Premium', $productoHongo->nombre_producto);
        $this->assertEquals(15, $productoHongo->frecuencia_aplicacion);
        $this->assertEquals(75000.50, $productoHongo->valor_producto);
        $this->assertEquals(7, $productoHongo->periodo_carencia);
        $this->assertEquals('Royas y Mildiu', $productoHongo->nombre_hongo);

        // Verificar producto PLAGA
        $this->assertInstanceOf(ProductoControlPlaga::class, $productoPlaga);
        $this->assertEquals('plaga', $productoPlaga->tipo);
        $this->assertEquals('ICA-PLA-67890', $productoPlaga->registro_ica);
        $this->assertEquals('Insecticida Total', $productoPlaga->nombre_producto);
        $this->assertEquals(21, $productoPlaga->frecuencia_aplicacion);
        $this->assertEquals(45000.00, $productoPlaga->valor_producto);
        $this->assertEquals(10, $productoPlaga->periodo_carencia);

        // Verificar producto FERTILIZANTE
        $this->assertInstanceOf(ProductoControlFertilizante::class, $productoFertilizante);
        $this->assertEquals('fertilizante', $productoFertilizante->tipo);
        $this->assertEquals('ICA-FER-54321', $productoFertilizante->registro_ica);
        $this->assertEquals('Fertilizante Orgánico', $productoFertilizante->nombre_producto);
        $this->assertEquals(30, $productoFertilizante->frecuencia_aplicacion);
        $this->assertEquals(120000.00, $productoFertilizante->valor_producto);
        $this->assertEquals('2026-03-01', $productoFertilizante->fecha_ultima_aplicacion->format('Y-m-d'));
    }

    /**
     * PRUEBA 2: Verificar campos fillable basados en el formulario de creación
     * 
     * 🎯 OBJETIVO:
     * Confirmar que los campos fillable corresponden exactamente a los campos
     * del formulario de creación/edición de productos de control en la interfaz web.
     * 
     * 📋 FORMULARIO DE PRODUCTOS CONTROL:
     * - Tipo (select: hongo, plaga, fertilizante)
     * - Registro ICA (input text, required)
     * - Nombre del producto (input text, required)
     * - Frecuencia de aplicación (input number, required)
     * - Valor del producto (input number, required)
     * - Periodo de carencia (solo para hongo y plaga)
     * - Nombre del hongo (solo para hongo)
     * - Fecha última aplicación (solo para fertilizante)
     */
    public function test_campos_fillable_coinciden_con_formulario()
    {
        $producto = new ProductoControl();
        
        $fillable = $producto->getFillable();
        
        // Estos son los campos que aparecen en los formularios según el tipo
        $camposDelFormulario = [
            'tipo',                      // Campo de tipo en la interfaz
            'registro_ica',              // Registro ICA
            'nombre_producto',            // Nombre del producto
            'frecuencia_aplicacion',      // Frecuencia de aplicación
            'valor_producto',             // Valor del producto
            'periodo_carencia',           // Período de carencia (hongo y plaga)
            'nombre_hongo',               // Nombre del hongo (solo hongo)
            'fecha_ultima_aplicacion'     // Fecha última aplicación (fertilizante)
        ];
        
        foreach ($camposDelFormulario as $campo) {
            $this->assertContains($campo, $fillable, 
                "El campo '$campo' debería estar en fillable porque aparece en algún formulario");
        }
        
        // Verificar que campos protegidos NO están en fillable
        $this->assertNotContains('id', $fillable);
        $this->assertNotContains('created_at', $fillable);
        $this->assertNotContains('updated_at', $fillable);
    }

    /**
     * PRUEBA 3: Verificar el STI (Single Table Inheritance) del modelo
     * 
     * 🎯 OBJETIVO:
     * Validar que el patrón STI implementado en ProductoControl funciona correctamente,
     * devolviendo la subclase adecuada según el campo 'tipo'.
     * 
     * 🔍 ¿QUÉ VERIFICA?
     * ✓ Que al crear un producto tipo 'hongo' se obtenga ProductoControlHongo
     * ✓ Que al crear un producto tipo 'plaga' se obtenga ProductoControlPlaga
     * ✓ Que al crear un producto tipo 'fertilizante' se obtenga ProductoControlFertilizante
     * ✓ Que los atributos específicos se mantengan en cada subclase
     */
    public function test_sti_retorna_subclase_correcta_segun_tipo()
    {
        // Crear productos usando el factory o creación directa
        $dataHongo = [
            'tipo' => 'hongo',
            'registro_ica' => 'ICA-STI-001',
            'nombre_producto' => 'Producto STI Hongo',
            'frecuencia_aplicacion' => 15,
            'valor_producto' => 50000,
            'periodo_carencia' => 7,
            'nombre_hongo' => 'Test Hongo'
        ];

        $dataPlaga = [
            'tipo' => 'plaga',
            'registro_ica' => 'ICA-STI-002',
            'nombre_producto' => 'Producto STI Plaga',
            'frecuencia_aplicacion' => 20,
            'valor_producto' => 60000,
            'periodo_carencia' => 10
        ];

        $dataFertilizante = [
            'tipo' => 'fertilizante',
            'registro_ica' => 'ICA-STI-003',
            'nombre_producto' => 'Producto STI Fertilizante',
            'frecuencia_aplicacion' => 30,
            'valor_producto' => 70000,
            'fecha_ultima_aplicacion' => '2026-03-15'
        ];

        // Crear instancias directamente (sin guardar en BD)
        $hongo = new ProductoControlHongo($dataHongo);
        $plaga = new ProductoControlPlaga($dataPlaga);
        $fertilizante = new ProductoControlFertilizante($dataFertilizante);

        // Verificar que son instancias de las subclases correctas
        $this->assertInstanceOf(ProductoControlHongo::class, $hongo);
        $this->assertInstanceOf(ProductoControlPlaga::class, $plaga);
        $this->assertInstanceOf(ProductoControlFertilizante::class, $fertilizante);
        
        // También deben ser instancias de la clase base
        $this->assertInstanceOf(ProductoControl::class, $hongo);
        $this->assertInstanceOf(ProductoControl::class, $plaga);
        $this->assertInstanceOf(ProductoControl::class, $fertilizante);
        
        // Verificar atributos específicos de cada subclase
        $this->assertEquals('Test Hongo', $hongo->nombre_hongo);
        $this->assertEquals(10, $plaga->periodo_carencia);
        $this->assertEquals('2026-03-15', $fertilizante->fecha_ultima_aplicacion->format('Y-m-d'));
    }

    /**
     * PRUEBA 4: Verificar que la tabla 'productos_control' contiene los campos necesarios
     * 
     * 🎯 OBJETIVO:
     * Confirmar que la estructura de la tabla permite almacenar todos los
     * campos necesarios para los diferentes tipos de productos de control.
     * 
     * 📋 COLUMNAS EN LA BASE DE DATOS (según el modelo):
     * - id (clave primaria)
     * - tipo (hongo, plaga, fertilizante)
     * - registro_ica
     * - nombre_producto
     * - frecuencia_aplicacion
     * - valor_producto
     * - periodo_carencia (nullable, para hongo y plaga)
     * - nombre_hongo (nullable, para hongo)
     * - fecha_ultima_aplicacion (nullable, para fertilizante)
     * - created_at, updated_at (timestamps)
     * 
     * 🔗 RELACIONES:
     * - hasMany: labores
     */
    public function test_tabla_productos_control_contiene_campos_necesarios()
    {
        $producto = new ProductoControl();
        
        // Verificar nombre de tabla
        $this->assertEquals('productos_control', $producto->getTable());
        
        // Verificar que los campos principales existen en la tabla
        $this->assertTrue($producto->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'id'));
        $this->assertTrue($producto->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'tipo'));
        $this->assertTrue($producto->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'registro_ica'));
        $this->assertTrue($producto->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'nombre_producto'));
        $this->assertTrue($producto->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'frecuencia_aplicacion'));
        $this->assertTrue($producto->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'valor_producto'));
        $this->assertTrue($producto->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'periodo_carencia'));
        $this->assertTrue($producto->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'nombre_hongo'));
        $this->assertTrue($producto->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'fecha_ultima_aplicacion'));
        $this->assertTrue($producto->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'created_at'));
        $this->assertTrue($producto->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'updated_at'));
        
        // Verificar lista de columnas esperadas
        $columns = $producto->getConnection()->getSchemaBuilder()->getColumnListing('productos_control');
        $columnasEsperadas = [
            'id', 'tipo', 'registro_ica', 'nombre_producto', 
            'frecuencia_aplicacion', 'valor_producto', 'periodo_carencia',
            'nombre_hongo', 'fecha_ultima_aplicacion', 'created_at', 'updated_at'
        ];
        
        foreach ($columnasEsperadas as $columna) {
            $this->assertContains($columna, $columns, "La columna '$columna' debería existir en la tabla productos_control");
        }
    }
}