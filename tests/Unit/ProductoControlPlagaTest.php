<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\ProductoControlPlaga;
use App\Models\ProductoControl;
use App\Models\Labor;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductoControlPlagaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * PRUEBA 1: Verificar creación de producto control plaga con datos reales del sistema
     * 
     * 🎯 OBJETIVO:
     * Validar que podemos crear un producto de control tipo plaga usando la misma 
     * estructura de datos que se maneja en el sistema.
     * Crea una instancia de ProductoControlPlaga sin guardar en BD.
     * 
     * 📊 DATOS DEL SISTEMA (basado en los controladores y la interfaz):
     * - Tipo: plaga (se asigna automáticamente)
     * - Registro ICA: Identificador único del producto
     * - Nombre del producto: Identificación comercial
     * - Frecuencia de aplicación: Cada cuántos días se aplica
     * - Valor del producto: Precio del producto
     * - Periodo de carencia: Días entre última aplicación y cosecha (atributo específico)
     * 
     * En la interfaz de labores se muestran plagas como:
     * - "Plaga consequatur sed et"
     * - "Plaga consequatur sed et" (aparece múltiples veces en la interfaz)
     */
    public function test_puede_crear_plaga_como_en_el_sistema()
    {
        // Crear producto plaga con datos similares al sistema
        $plaga = new ProductoControlPlaga([
            'tipo' => 'plaga', // Aunque el modelo lo asigna automáticamente
            'registro_ica' => 'ICA-PLA-12345',
            'nombre_producto' => 'Insecticida Total', // Similar a "Plaga consequatur sed et"
            'frecuencia_aplicacion' => 21, // Cada 21 días
            'valor_producto' => 45000.75,
            'periodo_carencia' => 10 // Días de carencia después de aplicación (atributo específico)
        ]);

        $this->assertInstanceOf(ProductoControlPlaga::class, $plaga);
        $this->assertInstanceOf(ProductoControl::class, $plaga); // También es instancia de la clase base
        
        // Verificar atributos comunes
        $this->assertEquals('plaga', $plaga->tipo);
        $this->assertEquals('ICA-PLA-12345', $plaga->registro_ica);
        $this->assertEquals('Insecticida Total', $plaga->nombre_producto);
        $this->assertEquals(21, $plaga->frecuencia_aplicacion);
        $this->assertEquals(45000.75, $plaga->valor_producto);
        
        // Verificar atributo específico de plaga
        $this->assertEquals(10, $plaga->periodo_carencia);
        
        // Verificar que los atributos específicos de otros tipos NO existen
        $this->assertNull($plaga->nombre_hongo); // Es para hongo
        $this->assertNull($plaga->fecha_ultima_aplicacion); // Es para fertilizante
    }

    /**
     * PRUEBA 2: Verificar que el modelo asigna automáticamente el tipo 'plaga'
     * 
     * 🎯 OBJETIVO:
     * Confirmar que el modelo ProductoControlPlaga establece automáticamente
     * el campo 'tipo' como 'plaga' al crear un nuevo registro.
     * 
     * 🔍 ¿QUÉ VERIFICA?
     * ✓ Que el evento creating del modelo asigna el tipo correctamente
     * ✓ Que no es necesario especificar el tipo manualmente
     * ✓ Que el STI funciona correctamente para plagas
     */
    public function test_asigna_automaticamente_tipo_plaga()
    {
        // Crear sin especificar tipo (el modelo debe asignarlo automáticamente)
        $plaga = ProductoControlPlaga::create([
            'registro_ica' => 'ICA-PLA-11111',
            'nombre_producto' => 'Insecticida Automático',
            'frecuencia_aplicacion' => 18,
            'valor_producto' => 38000,
            'periodo_carencia' => 8
        ]);

        // El tipo debería ser 'plaga' automáticamente
        $this->assertEquals('plaga', $plaga->tipo);
        
        // Verificar que el modelo se comporta como plaga
        $this->assertInstanceOf(ProductoControlPlaga::class, $plaga);
        
        // Guardar y verificar que se mantiene el tipo
        $plaga->save();
        $this->assertDatabaseHas('productos_control', [
            'id' => $plaga->id,
            'tipo' => 'plaga'
        ]);
        
        // Recuperar de BD y verificar que sigue siendo la subclase correcta
        $recuperado = ProductoControlPlaga::find($plaga->id);
        $this->assertInstanceOf(ProductoControlPlaga::class, $recuperado);
        $this->assertEquals('plaga', $recuperado->tipo);
    }

    /**
     * PRUEBA 3: Verificar el scope para filtrar solo plagas
     * 
     * 🎯 OBJETIVO:
     * Validar que el scope 'dePlaga' funciona correctamente y permite
     * obtener únicamente los productos de tipo plaga.
     * 
     * 📊 Escenario:
     * - Crear varios productos de diferentes tipos
     * - Aplicar el scope dePlaga
     * - Verificar que solo retorna plagas
     */
    public function test_scope_dePlaga_filtra_correctamente()
    {
        // Crear productos plaga
        $plaga1 = ProductoControlPlaga::create([
            'registro_ica' => 'ICA-PLA-001',
            'nombre_producto' => 'Insecticida A',
            'frecuencia_aplicacion' => 15,
            'valor_producto' => 40000,
            'periodo_carencia' => 7
        ]);

        $plaga2 = ProductoControlPlaga::create([
            'registro_ica' => 'ICA-PLA-002',
            'nombre_producto' => 'Insecticida B',
            'frecuencia_aplicacion' => 20,
            'valor_producto' => 55000,
            'periodo_carencia' => 12
        ]);

        // Crear un producto de otro tipo (hongo)
        $hongo = \App\Models\ProductoControlHongo::create([
            'registro_ica' => 'ICA-HGO-001',
            'nombre_producto' => 'Fungicida Test',
            'frecuencia_aplicacion' => 25,
            'valor_producto' => 60000,
            'periodo_carencia' => 5,
            'nombre_hongo' => 'Test Hongo'
        ]);

        // Aplicar el scope dePlaga
        $plagas = ProductoControlPlaga::dePlaga()->get();
        
        // Verificar que solo retorna plagas
        $this->assertCount(2, $plagas);
        $this->assertEquals('Insecticida A', $plagas[0]->nombre_producto);
        $this->assertEquals('Insecticida B', $plagas[1]->nombre_producto);
        
        // Verificar que el hongo NO está incluido
        $nombres = $plagas->pluck('nombre_producto')->toArray();
        $this->assertNotContains('Fungicida Test', $nombres);
        
        // Todos deben ser instancias de ProductoControlPlaga
        foreach ($plagas as $plaga) {
            $this->assertInstanceOf(ProductoControlPlaga::class, $plaga);
            $this->assertEquals('plaga', $plaga->tipo);
            $this->assertNotNull($plaga->periodo_carencia);
            $this->assertNull($plaga->nombre_hongo);
            $this->assertNull($plaga->fecha_ultima_aplicacion);
        }
    }

    /**
     * PRUEBA 4: Verificar que la tabla 'productos_control' contiene los campos necesarios para plagas
     * 
     * 🎯 OBJETIVO:
     * Confirmar que la estructura de la tabla permite almacenar todos los
     * campos necesarios para productos tipo plaga, incluyendo los específicos.
     * 
     * 📋 COLUMNAS EN LA BASE DE DATOS PARA PLAGAS:
     * - id (clave primaria)
     * - tipo (debe ser 'plaga')
     * - registro_ica
     * - nombre_producto
     * - frecuencia_aplicacion
     * - valor_producto
     * - periodo_carencia (campo específico de plaga y hongo)
     * - created_at, updated_at (timestamps)
     * 
     * 🔗 RELACIONES:
     * - labores(): HasMany - Una plaga puede estar en muchas labores
     */
    public function test_tabla_productos_control_contiene_campos_para_plagas()
    {
        $plaga = new ProductoControlPlaga();
        
        // Verificar nombre de tabla (hereda de ProductoControl)
        $this->assertEquals('productos_control', $plaga->getTable());
        
        // Verificar que los campos específicos de plaga existen
        $this->assertTrue($plaga->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'periodo_carencia'));
        
        // Verificar que los campos comunes existen
        $this->assertTrue($plaga->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'id'));
        $this->assertTrue($plaga->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'tipo'));
        $this->assertTrue($plaga->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'registro_ica'));
        $this->assertTrue($plaga->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'nombre_producto'));
        $this->assertTrue($plaga->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'frecuencia_aplicacion'));
        $this->assertTrue($plaga->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'valor_producto'));
        $this->assertTrue($plaga->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'created_at'));
        $this->assertTrue($plaga->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'updated_at'));
        
        // Verificar que los campos específicos de otros tipos también existen (son parte de la misma tabla)
        $this->assertTrue($plaga->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'nombre_hongo'));
        $this->assertTrue($plaga->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'fecha_ultima_aplicacion'));
        
        // Verificar lista de columnas esperadas
        $columns = $plaga->getConnection()->getSchemaBuilder()->getColumnListing('productos_control');
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
     * PRUEBA 5: Verificar que periodo_carencia puede ser cero
     * 
     * Según las reglas de validación, periodo_carencia puede ser mínimo 0.
     */
    public function test_periodo_carencia_puede_ser_cero()
    {
        $plaga = new ProductoControlPlaga([
            'registro_ica' => 'ICA-PLA-000',
            'nombre_producto' => 'Insecticida Sin Carencia',
            'frecuencia_aplicacion' => 10,
            'valor_producto' => 30000,
            'periodo_carencia' => 0 // Cero días de carencia
        ]);

        $this->assertEquals(0, $plaga->periodo_carencia);
        $this->assertIsInt($plaga->periodo_carencia);
    }
}