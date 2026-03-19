<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\ProductoControlFertilizante;
use App\Models\ProductoControl;
use App\Models\Labor;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductoControlFertilizanteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * PRUEBA 1: Verificar creación de producto control fertilizante con datos reales del sistema
     * 
     * 🎯 OBJETIVO:
     * Validar que podemos crear un producto de control tipo fertilizante usando la misma 
     * estructura de datos que se maneja en el sistema.
     * Crea una instancia de ProductoControlFertilizante sin guardar en BD.
     * 
     * 📊 DATOS DEL SISTEMA (basado en los controladores y la interfaz):
     * - Tipo: fertilizante (se asigna automáticamente)
     * - Registro ICA: Identificador único del producto
     * - Nombre del producto: Identificación comercial
     * - Frecuencia de aplicación: Cada cuántos días se aplica
     * - Valor del producto: Precio del producto
     * - Fecha última aplicación: Cuándo se aplicó por última vez (atributo específico)
     * 
     * En la interfaz de labores se muestran fertilizantes como:
     * - "Fertilizante tenetur et suscipit"
     * - "Fertilizante molestias minima inventore"
     */
    public function test_puede_crear_fertilizante_como_en_el_sistema()
    {
        // Crear producto fertilizante con datos similares al sistema
        $fertilizante = new ProductoControlFertilizante([
            'tipo' => 'fertilizante', // Aunque el modelo lo asigna automáticamente
            'registro_ica' => 'ICA-FER-98765',
            'nombre_producto' => 'Fertilizante Orgánico Premium', // Similar a los mostrados en interfaz
            'frecuencia_aplicacion' => 30, // Cada 30 días
            'valor_producto' => 125000.50,
            'fecha_ultima_aplicacion' => '2026-03-15' // Atributo específico de fertilizante
        ]);

        $this->assertInstanceOf(ProductoControlFertilizante::class, $fertilizante);
        $this->assertInstanceOf(ProductoControl::class, $fertilizante); // También es instancia de la clase base
        
        // Verificar atributos comunes
        $this->assertEquals('fertilizante', $fertilizante->tipo);
        $this->assertEquals('ICA-FER-98765', $fertilizante->registro_ica);
        $this->assertEquals('Fertilizante Orgánico Premium', $fertilizante->nombre_producto);
        $this->assertEquals(30, $fertilizante->frecuencia_aplicacion);
        $this->assertEquals(125000.50, $fertilizante->valor_producto);
        
        // Verificar atributo específico de fertilizante
        $this->assertEquals('2026-03-15', $fertilizante->fecha_ultima_aplicacion->format('Y-m-d'));
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $fertilizante->fecha_ultima_aplicacion);
        
        // Verificar que los atributos específicos de otros tipos NO existen
        $this->assertNull($fertilizante->nombre_hongo);
        $this->assertNull($fertilizante->periodo_carencia); // periodo_carencia es para hongo y plaga
    }

    /**
     * PRUEBA 2: Verificar que el modelo asigna automáticamente el tipo 'fertilizante'
     * 
     * 🎯 OBJETIVO:
     * Confirmar que el modelo ProductoControlFertilizante establece automáticamente
     * el campo 'tipo' como 'fertilizante' al crear un nuevo registro.
     * 
     * 🔍 ¿QUÉ VERIFICA?
     * ✓ Que el evento creating del modelo asigna el tipo correctamente
     * ✓ Que no es necesario especificar el tipo manualmente
     * ✓ Que el STI funciona correctamente para fertilizantes
     */
    public function test_asigna_automaticamente_tipo_fertilizante()
    {
        // Crear y guardar sin especificar tipo (el modelo debe asignarlo automáticamente durante el save)
        $fertilizante = ProductoControlFertilizante::create([
            'registro_ica' => 'ICA-FER-11111',
            'nombre_producto' => 'Fertilizante Automático',
            'frecuencia_aplicacion' => 15,
            'valor_producto' => 80000,
            'fecha_ultima_aplicacion' => '2026-03-10'
        ]);

        // El tipo debería ser 'fertilizante' automáticamente asignado durante el create
        $this->assertEquals('fertilizante', $fertilizante->tipo);
        
        // Verificar que el modelo se comporta como fertilizante
        $this->assertInstanceOf(ProductoControlFertilizante::class, $fertilizante);
        
        // Verificar que se mantiene el tipo en BD
        $this->assertDatabaseHas('productos_control', [
            'id' => $fertilizante->id,
            'tipo' => 'fertilizante'
        ]);
        
        // Recuperar de BD y verificar que sigue siendo la subclase correcta
        $recuperado = ProductoControlFertilizante::find($fertilizante->id);
        $this->assertInstanceOf(ProductoControlFertilizante::class, $recuperado);
        $this->assertEquals('fertilizante', $recuperado->tipo);
    }

    /**
     * PRUEBA 3: Verificar el scope para filtrar solo fertilizantes
     * 
     * 🎯 OBJETIVO:
     * Validar que el scope 'deFertilizante' funciona correctamente y permite
     * obtener únicamente los productos de tipo fertilizante.
     * 
     * 📊 Escenario:
     * - Crear varios productos de diferentes tipos
     * - Aplicar el scope deFertilizante
     * - Verificar que solo retorna fertilizantes
     */
    public function test_scope_deFertilizante_filtra_correctamente()
    {
        // Crear productos de diferentes tipos
        $fertilizante1 = ProductoControlFertilizante::create([
            'registro_ica' => 'ICA-FER-001',
            'nombre_producto' => 'Fertilizante A',
            'frecuencia_aplicacion' => 20,
            'valor_producto' => 50000,
            'fecha_ultima_aplicacion' => '2026-03-01'
        ]);

        $fertilizante2 = ProductoControlFertilizante::create([
            'registro_ica' => 'ICA-FER-002',
            'nombre_producto' => 'Fertilizante B',
            'frecuencia_aplicacion' => 30,
            'valor_producto' => 75000,
            'fecha_ultima_aplicacion' => '2026-03-05'
        ]);

        // Crear un producto de otro tipo (hongo) - asumiendo que existe el factory
        $hongo = \App\Models\ProductoControlHongo::create([
            'registro_ica' => 'ICA-HGO-001',
            'nombre_producto' => 'Fungicida Test',
            'frecuencia_aplicacion' => 15,
            'valor_producto' => 60000,
            'periodo_carencia' => 7,
            'nombre_hongo' => 'Test Hongo'
        ]);

        // Aplicar el scope deFertilizante
        $fertilizantes = ProductoControlFertilizante::deFertilizante()->get();
        
        // Verificar que solo retorna fertilizantes
        $this->assertCount(2, $fertilizantes);
        $this->assertEquals('Fertilizante A', $fertilizantes[0]->nombre_producto);
        $this->assertEquals('Fertilizante B', $fertilizantes[1]->nombre_producto);
        
        // Verificar que el hongo NO está incluido
        $nombres = $fertilizantes->pluck('nombre_producto')->toArray();
        $this->assertNotContains('Fungicida Test', $nombres);
        
        // Todos deben ser instancias de ProductoControlFertilizante
        foreach ($fertilizantes as $fertilizante) {
            $this->assertInstanceOf(ProductoControlFertilizante::class, $fertilizante);
        }
    }

    /**
     * PRUEBA 4: Verificar que la tabla 'productos_control' contiene los campos necesarios para fertilizantes
     * 
     * 🎯 OBJETIVO:
     * Confirmar que la estructura de la tabla permite almacenar todos los
     * campos necesarios para productos tipo fertilizante, incluyendo los específicos.
     * 
     * 📋 COLUMNAS EN LA BASE DE DATOS PARA FERTILIZANTES:
     * - id (clave primaria)
     * - tipo (debe ser 'fertilizante')
     * - registro_ica
     * - nombre_producto
     * - frecuencia_aplicacion
     * - valor_producto
     * - fecha_ultima_aplicacion (campo específico de fertilizante, nullable)
     * - created_at, updated_at (timestamps)
     * 
     * 🔗 RELACIONES:
     * - labores(): HasMany - Un fertilizante puede estar en muchas labores
     */
    public function test_tabla_productos_control_contiene_campos_para_fertilizantes()
    {
        $fertilizante = new ProductoControlFertilizante();
        
        // Verificar nombre de tabla (hereda de ProductoControl)
        $this->assertEquals('productos_control', $fertilizante->getTable());
        
        // Verificar que los campos específicos de fertilizante existen
        $this->assertTrue($fertilizante->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'fecha_ultima_aplicacion'));
        
        // Verificar que los campos comunes existen
        $this->assertTrue($fertilizante->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'id'));
        $this->assertTrue($fertilizante->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'tipo'));
        $this->assertTrue($fertilizante->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'registro_ica'));
        $this->assertTrue($fertilizante->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'nombre_producto'));
        $this->assertTrue($fertilizante->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'frecuencia_aplicacion'));
        $this->assertTrue($fertilizante->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'valor_producto'));
        $this->assertTrue($fertilizante->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'created_at'));
        $this->assertTrue($fertilizante->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'updated_at'));
        
        // Verificar que los campos específicos de otros tipos también existen (son parte de la misma tabla)
        $this->assertTrue($fertilizante->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'periodo_carencia'));
        $this->assertTrue($fertilizante->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'nombre_hongo'));
        
        // Verificar lista de columnas esperadas
        $columns = $fertilizante->getConnection()->getSchemaBuilder()->getColumnListing('productos_control');
        $columnasEsperadas = [
            'id', 'tipo', 'registro_ica', 'nombre_producto', 
            'frecuencia_aplicacion', 'valor_producto', 'periodo_carencia',
            'nombre_hongo', 'fecha_ultima_aplicacion', 'created_at', 'updated_at'
        ];
        
        foreach ($columnasEsperadas as $columna) {
            $this->assertContains($columna, $columns, "La columna '$columna' debería existir en la tabla productos_control");
        }
    }

    /**
     * PRUEBA 5: Verificar cast de fecha_ultima_aplicacion
     * 
     * Esta prueba verifica que el campo fecha_ultima_aplicacion se castea correctamente
     * a objeto Carbon (heredado del cast en ProductoControl).
     */
    public function test_fecha_ultima_aplicacion_se_castea_a_carbon()
    {
        $fertilizante = new ProductoControlFertilizante([
            'fecha_ultima_aplicacion' => '2026-03-17'
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $fertilizante->fecha_ultima_aplicacion);
        $this->assertEquals('2026-03-17', $fertilizante->fecha_ultima_aplicacion->format('Y-m-d'));
        $this->assertEquals(2026, $fertilizante->fecha_ultima_aplicacion->year);
        $this->assertEquals(3, $fertilizante->fecha_ultima_aplicacion->month);
        $this->assertEquals(17, $fertilizante->fecha_ultima_aplicacion->day);
    }
}