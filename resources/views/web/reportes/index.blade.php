@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">📊 Módulo de Reportes</h1>
        </div>
    </div>
    <div class="row">
        <!-- Consulta A -->
        <div class="col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Consulta A
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Labores ejecutadas en un Vivero
                            </div>
                            <p class="mt-2 text-muted small">
                                Consulta todas las labores realizadas en un vivero con detalle de productos de control aplicados.
                            </p>
                            <a href="{{ route('reportes.labores-vivero') }}" class="btn btn-primary btn-sm mt-2">
                                <i class="fas fa-search"></i> Ir a consulta
                            </a>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-seedling fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Consulta B -->
        <div class="col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Consulta B
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Viveros de un Productor
                            </div>
                            <p class="mt-2 text-muted small">
                                Lista todos los viveros que pertenecen a un productor específico.
                            </p>
                            <a href="{{ route('reportes.viveros-productor') }}" class="btn btn-success btn-sm mt-2">
                                <i class="fas fa-search"></i> Ir a consulta
                            </a>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection