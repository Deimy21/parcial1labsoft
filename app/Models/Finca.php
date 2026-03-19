<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Finca
 * Identificada por número de catastro y municipio de ubicación.
 * Pertenece a un Productor y puede tener varios Viveros.
 */
class Finca extends Model
{
    use HasFactory;

    protected $table = 'fincas';

    protected $fillable = [
        'numero_catastro',
        'municipio',
        'productor_id',
    ];

    /**
     * Una Finca pertenece a un Productor.
     */
    public function productor()
    {
        return $this->belongsTo(Productor::class);
    }

    /**
     * Una Finca puede tener varios Viveros.
     */
    public function viveros()
    {
        return $this->hasMany(Vivero::class);
    }
}
