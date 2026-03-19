<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ProductoControl — Clase base para los productos de control de Labores.
 *
 * Tipos soportados (campo `tipo`):
 *   - hongo        → ProductoControlHongo
 *   - plaga        → ProductoControlPlaga
 *   - fertilizante → ProductoControlFertilizante
 *
 * Atributos comunes:
 *   - registro_ica
 *   - nombre_producto
 *   - frecuencia_aplicacion  (cada cuántos días se aplica)
 *   - valor_producto
 *
 * Atributos por subtipo (pueden ser null según tipo):
 *   - periodo_carencia         (hongo, plaga)
 *   - nombre_hongo             (hongo)
 *   - fecha_ultima_aplicacion  (fertilizante)
 */
class ProductoControl extends Model
{
    use HasFactory;

    protected $table = 'productos_control';

    /**
     * Mapa tipo → clase concreta para Single Table Inheritance.
     */
    protected static array $tipoMap = [
        'hongo'        => ProductoControlHongo::class,
        'plaga'        => ProductoControlPlaga::class,
        'fertilizante' => ProductoControlFertilizante::class,
    ];

    protected $fillable = [
        'tipo',
        'registro_ica',
        'nombre_producto',
        'frecuencia_aplicacion',
        'valor_producto',
        // Hongo & Plaga
        'periodo_carencia',
        // Hongo
        'nombre_hongo',
        // Fertilizante
        'fecha_ultima_aplicacion',
    ];

    protected $casts = [
        'valor_producto'          => 'decimal:2',
        'frecuencia_aplicacion'   => 'integer',
        'fecha_ultima_aplicacion' => 'date',
    ];

    /**
     * STI: Eloquent devuelve la subclase correcta según el campo `tipo`.
     */
    public function newFromBuilder($attributes = [], $connection = null): static
    {
        $attributes = (array) $attributes;
        $tipo  = $attributes['tipo'] ?? null;
        $class = static::$tipoMap[$tipo] ?? static::class;

        /** @var static $model */
        $model = (new $class)->newInstance([], true);
        $model->setRawAttributes((array) $attributes, true);
        $model->setConnection($connection ?: $this->getConnectionName());
        $model->fireModelEvent('retrieved', false);

        return $model;
    }

    /**
     * Un ProductoControl puede estar asociado a varias Labores.
     */

    public function labores()
    {
        return $this->hasMany(\App\Models\Labor::class, 'producto_control_id');
    }
    
}
