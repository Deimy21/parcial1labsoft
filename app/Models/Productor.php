<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Productor
 * Identificado por documento de identidad, nombre, apellido, teléfono y correo.
 * Puede poseer varias Fincas.
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
     * Un Productor puede tener varias Fincas.
     */
    public function fincas()
    {
        return $this->hasMany(Finca::class);
    }

    /**
     * Acceso a todos los Viveros del Productor (a través de sus Fincas).
     */
    public function viveros()
    {
        return $this->hasManyThrough(Vivero::class, Finca::class);
    }
}
