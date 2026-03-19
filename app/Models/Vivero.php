<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vivero extends Model
{
    use HasFactory;
    protected $fillable = [
        'codigo',
        'tipo_cultivo',
        'finca_id'
    ];

    // Relación: Un vivero pertenece a una finca
    public function finca(): BelongsTo
    {
        return $this->belongsTo(Finca::class);
    }

    // Relación: Un vivero tiene muchas labores
    public function labores(): HasMany
    {
        return $this->hasMany(Labor::class);
    }
}