<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\ProductoControlHongo;
use App\Models\ProductoControl;
use App\Models\Labor;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductoControlHongoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * PRUEBA 1: Verificar creación de producto control hongo con datos reales del sistema
     * 
     * 🎯 OBJETIVO:
     * Validar que podemos crear un producto de control tipo hongo usando la misma 
     * estructura de datos que se maneja en el sistema.
     * Crea una instancia de ProductoControlHongo sin guardar en BD.
     * 
     * 📊 DATOS DEL SISTEMA (basado en los controladores y la interfaz):
     * - Tipo: hongo (se asigna automáticamente)
     * - Registro ICA: Identificador único del producto
     * - Nombre del producto: Identificación comercial
     * - Frecuencia de aplicación: Cada cuántos días se aplica
     * - Valor del producto: Precio del producto
     * - Periodo de carencia: Días entre última aplicación y cosecha (atributo específico)
     * - Nombre del hongo: Nombre del hongo que controla (atributo específico)
     * 
     * En la interfaz de labores se muestran hongos como:
     * - "Hongo harum a eveniet"
     * - "Hongo mollitia esse illo"
     */
    public function test_puede_crear_hongo_como_en_el_sistema()
    {
        // Crear producto hongo con datos similares al sistema
        $hongo = new ProductoControlHongo([
            'tipo' => 'hongo', // Aunque el modelo lo asigna automáticamente
            'registro_ica' => 'ICA-HGO-12345',
            'nombre_producto' => 'Fungicida Premium', // Similar a "Hongo harum a eveniet"
            'frecuencia_aplicacion' => 15, // Cada 15 días
            'valor_producto' => 75000.50,
            'periodo_carencia' => 7, // Días de carencia después de aplicación (atributo específico)
            'nombre_hongo' => 'Royas y Mildiu' // Nombre del hongo a controlar (atributo específico)
        ]);

        $this->assertInstanceOf(ProductoControlHongo::class, $hongo);
        $this->assertInstanceOf(ProductoControl::class, $hongo); // También es instancia de la clase base
        
        // Verificar atributos comunes
        $this->assertEquals('hongo', $hongo->tipo);
        $this->assertEquals('ICA-HGO-12345', $hongo->registro_ica);
        $this->assertEquals('Fungicida Premium', $hongo->nombre_producto);
        $this->assertEquals(15, $hongo->frecuencia_aplicacion);
        $this->assertEquals(75000.50, $hongo->valor_producto);
        
        // Verificar atributos específicos de hongo
        $this->assertEquals(7, $hongo->periodo_carencia);
        $this->assertEquals('Royas y Mildiu', $hongo->nombre_hongo);
        
        // Verificar que los atributos específicos de otros tipos NO existen
        $this->assertNull($hongo->fecha_ultima_aplicacion); // Es para fertilizante
    }

    /**
     * PRUEBA 2: Verificar que el modelo asigna automáticamente el tipo 'hongo'
     * 
     * 🎯 OBJETIVO:
     * Confirmar que el modelo ProductoControlHongo establece automáticamente
     * el campo 'tipo' como 'hongo' al crear un nuevo registro.
     * 
     * 🔍 ¿QUÉ VERIFICA?
     * ✓ Que el evento creating del modelo asigna el tipo correctamente
     * ✓ Que no es necesario especificar el tipo manualmente
     * ✓ Que el STI funciona correctamente para hongos
     */
    public function test_asigna_automaticamente_tipo_hongo()
    {
        // Crear sin especificar tipo (el modelo debe asignarlo automáticamente)
        $hongo = ProductoControlHongo::create([
            'registro_ica' => 'ICA-HGO-11111',
            'nombre_producto' => 'Fungicida Automático',
            'frecuencia_aplicacion' => 20,
            'valor_producto' => 60000,
            'periodo_carencia' => 10,
            'nombre_hongo' => 'Oídio'
        ]);

        // El tipo debería ser 'hongo' automáticamente
        $this->assertEquals('hongo', $hongo->tipo);
        
        // Verificar que el modelo se comporta como hongo
        $this->assertInstanceOf(ProductoControlHongo::class, $hongo);
        
        // Guardar y verificar que se mantiene el tipo
        $hongo->save();
        $this->assertDatabaseHas('productos_control', [
            'id' => $hongo->id,
            'tipo' => 'hongo'
        ]);
        
        // Recuperar de BD y verificar que sigue siendo la subclase correcta
        $recuperado = ProductoControlHongo::find($hongo->id);
        $this->assertInstanceOf(ProductoControlHongo::class, $recuperado);
        $this->assertEquals('hongo', $recuperado->tipo);
    }

    /**
     * PRUEBA 3: Verificar el scope para filtrar solo hongos
     * 
     * 🎯 OBJETIVO:
     * Validar que el scope 'deHongo' funciona correctamente y permite
     * obtener únicamente los productos de tipo hongo.
     * 
     * 📊 Escenario:
     * - Crear varios productos de diferentes tipos
     * - Aplicar el scope deHongo
     * - Verificar que solo retorna hongos
     */
    public function test_scope_deHongo_filtra_correctamente()
    {
        // Crear productos hongo
        $hongo1 = ProductoControlHongo::create([
            'registro_ica' => 'ICA-HGO-001',
            'nombre_producto' => 'Fungicida A',
            'frecuencia_aplicacion' => 15,
            'valor_producto' => 50000,
            'periodo_carencia' => 7,
            'nombre_hongo' => 'Royas'
        ]);

        $hongo2 = ProductoControlHongo::create([
            'registro_ica' => 'ICA-HGO-002',
            'nombre_producto' => 'Fungicida B',
            'frecuencia_aplicacion' => 20,
            'valor_producto' => 65000,
            'periodo_carencia' => 10,
            'nombre_hongo' => 'Mildiu'
        ]);

        // Crear un producto de otro tipo (plaga)
        $plaga = \App\Models\ProductoControlPlaga::create([
            'registro_ica' => 'ICA-PLA-001',
            'nombre_producto' => 'Insecticida Test',
            'frecuencia_aplicacion' => 25,
            'valor_producto' => 55000,
            'periodo_carencia' => 5
        ]);

        // Aplicar el scope deHongo
        $hongos = ProductoControlHongo::deHongo()->get();
        
        // Verificar que solo retorna hongos
        $this->assertCount(2, $hongos);
        $this->assertEquals('Fungicida A', $hongos[0]->nombre_producto);
        $this->assertEquals('Fungicida B', $hongos[1]->nombre_producto);
        
        // Verificar que la plaga NO está incluida
        $nombres = $hongos->pluck('nombre_producto')->toArray();
        $this->assertNotContains('Insecticida Test', $nombres);
        
        // Todos deben ser instancias de ProductoControlHongo
        foreach ($hongos as $hongo) {
            $this->assertInstanceOf(ProductoControlHongo::class, $hongo);
            $this->assertEquals('hongo', $hongo->tipo);
            $this->assertNotNull($hongo->nombre_hongo);
            $this->assertNotNull($hongo->periodo_carencia);
        }
    }

    /**
     * PRUEBA 4: Verificar que la tabla 'productos_control' contiene los campos necesarios para hongos
     * 
     * 🎯 OBJETIVO:
     * Confirmar que la estructura de la tabla permite almacenar todos los
     * campos necesarios para productos tipo hongo, incluyendo los específicos.
     * 
     * 📋 COLUMNAS EN LA BASE DE DATOS PARA HONGOS:
     * - id (clave primaria)
     * - tipo (debe ser 'hongo')
     * - registro_ica
     * - nombre_producto
     * - frecuencia_aplicacion
     * - valor_producto
     * - periodo_carencia (campo específico de hongo y plaga)
     * - nombre_hongo (campo específico de hongo)
     * - created_at, updated_at (timestamps)
     * 
     * 🔗 RELACIONES:
     * - labores(): HasMany - Un hongo puede estar en muchas labores
     */
    public function test_tabla_productos_control_contiene_campos_para_hongos()
    {
        $hongo = new ProductoControlHongo();
        
        // Verificar nombre de tabla (hereda de ProductoControl)
        $this->assertEquals('productos_control', $hongo->getTable());
        
        // Verificar que los campos específicos de hongo existen
        $this->assertTrue($hongo->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'periodo_carencia'));
        $this->assertTrue($hongo->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'nombre_hongo'));
        
        // Verificar que los campos comunes existen
        $this->assertTrue($hongo->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'id'));
        $this->assertTrue($hongo->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'tipo'));
        $this->assertTrue($hongo->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'registro_ica'));
        $this->assertTrue($hongo->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'nombre_producto'));
        $this->assertTrue($hongo->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'frecuencia_aplicacion'));
        $this->assertTrue($hongo->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'valor_producto'));
        $this->assertTrue($hongo->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'created_at'));
        $this->assertTrue($hongo->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'updated_at'));
        
        // Verificar que los campos específicos de otros tipos también existen (son parte de la misma tabla)
        $this->assertTrue($hongo->getConnection()->getSchemaBuilder()->hasColumn('productos_control', 'fecha_ultima_aplicacion'));
        
        // Verificar lista de columnas esperadas
        $columns = $hongo->getConnection()->getSchemaBuilder()->getColumnListing('productos_control');
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