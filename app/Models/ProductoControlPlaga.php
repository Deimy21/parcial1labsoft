<?php

namespace App\Models;

/**
 * ProductoControlPlaga
 *
 * Extiende ProductoControl con los atributos específicos para control de plagas:
 *   - periodo_carencia : tiempo legalmente establecido (días) entre última aplicación
 *                        de un fitosanitario y la cosecha.
 */
class ProductoControlPlaga extends ProductoControl
{
    protected static function booted(): void
    {
        static::creating(function (self $model) {
            $model->tipo = 'plaga';
        });
    }

    /**
     * Scope para filtrar únicamente productos de control de plagas.
     */
    public function scopeDePlaga($query)
    {
        return $query->where('tipo', 'plaga');
    }
}
