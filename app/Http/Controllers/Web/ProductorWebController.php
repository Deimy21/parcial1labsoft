<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Productor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductorWebController extends Controller
{
    public function index(Request $request)
    {
        $query = Productor::withCount('fincas');

        if ($request->filled('documento')) {
            $query->where('documento_identidad', 'like', '%'.$request->documento.'%');
        }

        $productores = $query->orderBy('apellido')->orderBy('nombre')->paginate(15);

        return view('productores.index', compact('productores'));
    }

    public function create()
    {
        return view('productores.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'documento_identidad' => ['required', 'string', 'unique:productores,documento_identidad'],
            'nombre'              => ['required', 'string', 'max:100'],
            'apellido'            => ['required', 'string', 'max:100'],
            'telefono'            => ['required', 'string', 'max:20'],
            'correo'              => ['required', 'email', 'unique:productores,correo'],
        ]);

        Productor::create($data);
        return redirect()->route('productores.index')
            ->with('success', 'Productor creado correctamente.');
    }

    public function show(Productor $productor)
    {
        $productor->load('fincas.viveros');
        return view('productores.show', compact('productor'));
    }

    public function edit(Productor $productor)
    {
        return view('productores.edit', compact('productor'));
    }

    public function update(Request $request, Productor $productor)
    {
        $data = $request->validate([
            'documento_identidad' => ['required', 'string', Rule::unique('productores')->ignore($productor->id)],
            'nombre'              => ['required', 'string', 'max:100'],
            'apellido'            => ['required', 'string', 'max:100'],
            'telefono'            => ['required', 'string', 'max:20'],
            'correo'              => ['required', 'email', Rule::unique('productores')->ignore($productor->id)],
        ]);

        $productor->update($data);
        return redirect()->route('productores.show', $productor)
            ->with('success', 'Productor actualizado correctamente.');
    }

    public function destroy(Productor $productor)
    {
        $productor->delete();
        return redirect()->route('productores.index')
            ->with('success', 'Productor eliminado correctamente.');
    }
}
