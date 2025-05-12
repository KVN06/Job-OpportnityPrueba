<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    // Mostrar formulario para crear un nuevo portafolio
    public function create()
    {
        return view('portfolio.create');
    }

    // Guardar un nuevo portafolio en la base de datos
    public function store(Request $request)
    {
        // Validación de datos requeridos
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file_url' => 'required|string',
        ]);

        // Crear nuevo portafolio asociado al desempleado autenticado
        $portfolio = new Portfolio();
        $portfolio->unemployed_id = Auth::user()->unemployed->id;
        $portfolio->title = $request->title;
        $portfolio->description = $request->description;
        $portfolio->file_url = $request->file_url;
        $portfolio->save();

        return redirect()->route('portfolio-list');
    }

    // Mostrar todos los portafolios del usuario desempleado actual
    public function list()
    {
        $portfolios = Portfolio::where('unemployed_id', Auth::user()->unemployed->id)->get();
        return view('portfolio.list', compact('portfolios'));
    }

    // Mostrar formulario para editar un portafolio existente
    public function edit($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        return view('portfolio.edit', compact('portfolio'));
    }

    // Actualizar los datos de un portafolio existente
    public function update(Request $request, $id)
    {
        // Validar campos requeridos
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file_url' => 'required|string',
        ]);

        // Actualizar portafolio
        $portfolio = Portfolio::findOrFail($id);
        $portfolio->title = $request->title;
        $portfolio->description = $request->description;
        $portfolio->file_url = $request->file_url;
        $portfolio->save();

        return redirect()->route('portfolio-list');
    }

    // Eliminar un portafolio
    public function destroy($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        $portfolio->delete();

        return redirect()->route('portfolio-list');
    }
}
