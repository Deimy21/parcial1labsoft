<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Labor
 *
 * Actividad importante dentro del cultivo, asociada a un Vivero.
 * El tipo de Labor queda determinado por el ProductoControl empleado:
 *   - hongo        (usa ProductoControlHongo)
 *   - plaga        (usa ProductoControlPlaga)
 *   - fertilizante (usa ProductoControlFertilizante)
 *
 * Atributos:
 *   - fecha          : fecha en que se realiza la labor.
 *   - descripcion    : descripción de la labor.
 *   - vivero_id      : Vivero al que pertenece la labor.
 *   - producto_control_id : ProductoControl empleado en la labor.
 */
class Labor extends Model
{
    use HasFactory;

    protected $table = 'labores';

    protected $fillable = [
        'fecha',
        'descripcion',
        'vivero_id',
        'producto_control_id',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    /**
     * Una Labor pertenece a un Vivero.
     */
    public function vivero()
    {
        return $this->belongsTo(Vivero::class);
    }

    /**
     * Una Labor emplea un ProductoControl (puede ser Hongo, Plaga o Fertilizante).
     * Gracias al STI en ProductoControl::newFromBuilder, Eloquent retornará
     * automáticamente la subclase correspondiente.
     */
    public function productoControl()
    {
        return $this->belongsTo(ProductoControl::class);
    }

    /**
     * Devuelve el tipo de labor derivado del ProductoControl asociado.
     */
    public function getTipoAttribute(): ?string
    {
        return $this->productoControl?->tipo;
    }
}
