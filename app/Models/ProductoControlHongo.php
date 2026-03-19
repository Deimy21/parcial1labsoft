<?php

namespace App\Models;

/**
 * ProductoControlHongo
 *
 * Extiende ProductoControl con los atributos específicos para control de hongos:
 *   - periodo_carencia  : tiempo legalmente establecido (días) entre última aplicación y cosecha.
 *   - nombre_hongo      : nombre del hongo que afecta la planta.
 */
class ProductoControlHongo extends ProductoControl
{
    /**
     * El tipo fijo de este subtipo para asignación automática al crear.
     */
    protected static function booted(): void
    {
        static::creating(function (self $model) {
            $model->tipo = 'hongo';
        });
    }

    /**
     * Scope para filtrar únicamente productos de control de hongos.
     */
    public function scopeDeHongo($query)
    {
        return $query->where('tipo', 'hongo');
    }
}
