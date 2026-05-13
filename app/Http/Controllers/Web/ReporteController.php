<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Vivero;
use App\Models\Productor;
use App\Models\Labor;
use App\Exports\LaboresViveroExport;
use App\Exports\ViverosProductorExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReporteController extends Controller
{
    public function index()
    {
        return view('web.reportes.index');
    }

    // ──────────────────────────────────────────────
    // CONSULTA A: Labores de un Vivero
    // ──────────────────────────────────────────────
    public function laboresVivero()
    {
        $viveros = Vivero::with('productor')->orderBy('nombre')->get();
        $viveroSeleccionado = null;
        $labores = collect();

        if (request('vivero_id')) {
            $viveroSeleccionado = Vivero::with('productor')->findOrFail(request('vivero_id'));
            $labores = Labor::with('productoControl')
                ->where('vivero_id', $viveroSeleccionado->id)
                ->orderBy('fecha', 'desc')
                ->get();
        }

        return view('web.reportes.labores_vivero', compact('viveros', 'viveroSeleccionado', 'labores'));
    }

    public function laboresViveroPdf($id)
    {
        $vivero = Vivero::with('productor')->findOrFail($id);
        $labores = Labor::with('productoControl')
            ->where('vivero_id', $id)
            ->orderBy('fecha', 'desc')
            ->get();

        $pdf = Pdf::loadView('web.reportes.pdf.labores_vivero_pdf', compact('vivero', 'labores'))
            ->setPaper('a4', 'landscape');

        return $pdf->download("labores_vivero_{$vivero->nombre}.pdf");
    }

    public function laboresViveroExcel($id)
    {
        $vivero = Vivero::findOrFail($id);
        return Excel::download(
            new LaboresViveroExport($id),
            "labores_vivero_{$vivero->nombre}.xlsx"
        );
    }

    // ──────────────────────────────────────────────
    // CONSULTA B: Viveros de un Productor
    // ──────────────────────────────────────────────
    public function viverosProductor()
    {
        $productores = Productor::orderBy('nombre')->get();
        $productorSeleccionado = null;
        $viveros = collect();

        if (request('productor_id')) {
            $productorSeleccionado = Productor::findOrFail(request('productor_id'));
            $viveros = Vivero::where('productor_id', $productorSeleccionado->id)
                ->orderBy('nombre')
                ->get();
        }

        return view('web.reportes.viveros_productor', compact('productores', 'productorSeleccionado', 'viveros'));
    }

    public function viverosProductorPdf($id)
    {
        $productor = Productor::findOrFail($id);
        $viveros = Vivero::where('productor_id', $id)->orderBy('nombre')->get();

        $pdf = Pdf::loadView('web.reportes.pdf.viveros_productor_pdf', compact('productor', 'viveros'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("viveros_{$productor->nombre}_{$productor->apellido}.pdf");
    }

    public function viverosProductorExcel($id)
    {
        $productor = Productor::findOrFail($id);
        return Excel::download(
            new ViverosProductorExport($id),
            "viveros_{$productor->nombre}_{$productor->apellido}.xlsx"
        );
    }
}