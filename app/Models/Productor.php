<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Productor
 *
 * Identificado por documento de identidad, nombre y apellido.
 * (Se conservan teléfono y correo como atributos útiles de contacto.)
 *
 * Relación: Cada Productor puede ser propietario de varios Viveros.
 */
class Productor extends Model
{
    use HasFactory;

    protected $table = 'productores';

    protected $fillable = [
        'documento_identidad',
        'nombre',
        'apellido',
        'telefono',
        'correo',
    ];

    /**
     * Un Productor puede tener varios Viveros.
     */
    public function viveros(): HasMany
    {
        return $this->hasMany(Vivero::class);
    }
}
