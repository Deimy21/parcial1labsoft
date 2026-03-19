<?php

namespace App\Models;

/**
 * ProductoControlFertilizante
 *
 * Extiende ProductoControl con los atributos específicos para fertilizantes:
 *   - fecha_ultima_aplicacion : fecha de la última vez que se aplicó este producto.
 */
class ProductoControlFertilizante extends ProductoControl
{
    protected static function booted(): void
    {
        static::creating(function (self $model) {
            $model->tipo = 'fertilizante';
        });
    }

    /**
     * Scope para filtrar únicamente productos fertilizantes.
     */
    public function scopeDeFertilizante($query)
    {
        return $query->where('tipo', 'fertilizante');
    }
}
