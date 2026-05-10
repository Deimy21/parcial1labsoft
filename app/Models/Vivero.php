<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Vivero
 *
 * Identificado por un código (asignado por el Productor) y debe poseer
 * un nombre, departamento y municipio donde se encuentra.
 *
 * Relaciones:
 *   - Pertenece a un Productor.
 *   - Puede tener varias Labores.
 */
class Vivero extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'departamento',
        'municipio',
        'productor_id',
    ];

    /**
     * Un Vivero pertenece a un Productor.
     */
    public function productor(): BelongsTo
    {
        return $this->belongsTo(Productor::class);
    }

    /**
     * Un Vivero tiene muchas Labores.
     */
    public function labores(): HasMany
    {
        return $this->hasMany(Labor::class);
    }
}
