<?php

namespace App\Exports;

use App\Models\Labor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaboresViveroExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
{
    protected $viveroId;

    public function __construct($viveroId)
    {
        $this->viveroId = $viveroId;
    }

    public function collection()
    {
        $labores = Labor::with('productoControl')
            ->where('vivero_id', $this->viveroId)
            ->orderBy('fecha', 'desc')
            ->get();

        $rows = collect();

        foreach ($labores as $labor) {
            $p = $labor->productoControl;

            if (!$p) {
                $rows->push([
                    'Fecha'             => \Carbon\Carbon::parse($labor->fecha)->format('d/m/Y'),
                    'Descripción'       => $labor->descripcion,
                    'Tipo Producto'     => 'Sin producto',
                    'Nombre Producto'   => '-',
                    'Registro ICA'      => '-',
                    'Frecuencia (días)' => '-',
                    'Valor'             => '-',
                    'Detalle'           => '-',
                ]);
            } else {
                $detalle = match($p->tipo) {
                    'hongo'        => "Hongo: {$p->nombre_hongo} | Carencia: {$p->periodo_carencia} días",
                    'plaga'        => "Carencia: {$p->periodo_carencia} días",
                    'fertilizante' => "Última aplic.: " . ($p->fecha_ultima_aplicacion
                        ? \Carbon\Carbon::parse($p->fecha_ultima_aplicacion)->format('d/m/Y') : 'N/A'),
                    default        => '-',
                };

                $rows->push([
                    'Fecha'             => \Carbon\Carbon::parse($labor->fecha)->format('d/m/Y'),
                    'Descripción'       => $labor->descripcion,
                    'Tipo Producto'     => ucfirst($p->tipo),
                    'Nombre Producto'   => $p->nombre_producto,
                    'Registro ICA'      => $p->registro_ica,
                    'Frecuencia (días)' => $p->frecuencia_aplicacion,
                    'Valor'             => $p->valor_producto,
                    'Detalle'           => $detalle,
                ]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Fecha', 'Descripción Labor', 'Tipo Producto', 'Nombre Producto',
                'Registro ICA', 'Frecuencia (días)', 'Valor ($)', 'Detalle'];
    }

    public function title(): string
    {
        return 'Labores por Vivero';
    }
}