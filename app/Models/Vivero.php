<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vivero extends Model
{
    use HasFactory;

    protected $table = 'viveros';

    protected $fillable = [
        'codigo',
        'nombre',
        'departamento',
        'municipio',
        'productor_id',
    ];

    /**
     * Un vivero pertenece a un productor
     */
    public function productor(): BelongsTo
    {
        return $this->belongsTo(Productor::class, 'productor_id');
    }

    /**
     * (Opcional) Si aún usas labores, puedes dejarlo.
     * Si no lo usas, ELIMÍNALO.
     */
    public function labores(): HasMany
    {
        return $this->hasMany(Labor::class);
    }
}