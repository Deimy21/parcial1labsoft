<?php

namespace App\Exports;

use App\Models\Vivero;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ViverosProductorExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
{
    protected $productorId;

    public function __construct($productorId)
    {
        $this->productorId = $productorId;
    }

    public function collection()
    {
        return Vivero::where('productor_id', $this->productorId)
            ->orderBy('nombre')
            ->get()
            ->map(fn($v) => [
                'Código'       => $v->codigo,
                'Nombre'       => $v->nombre,
                'Departamento' => $v->departamento,
                'Municipio'    => $v->municipio,
            ]);
    }

    public function headings(): array
    {
        return ['Código', 'Nombre', 'Departamento', 'Municipio'];
    }

    public function title(): string
    {
        return 'Viveros por Productor';
    }
}