<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Services\PortfolioService;
use App\Http\Requests\PortfolioRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    protected $portfolioService;

    public function __construct(PortfolioService $portfolioService)
    {
        $this->middleware('auth');
        $this->middleware('role:unemployed')->except(['show', 'index']);
        $this->portfolioService = $portfolioService;
    }

    // Mostrar todos los portafolios del usuario desempleado actual
    public function index(Request $request)
    {
        try {
            $filters = $request->only([
                'search',
                'status',
                'technologies',
                'per_page'
            ]);

            $portfolios = $this->portfolioService->getFilteredPortfolios($filters, Auth::user());
            return view('portfolio.list', compact('portfolios'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar los portafolios: ' . $e->getMessage());
        }
    }

    // Mostrar formulario para crear un nuevo portafolio
    public function create()
    {
        return view('portfolio.create');
    }

    // Guardar un nuevo portafolio en la base de datos
    public function store(PortfolioRequest $request)
    {
        try {
            $portfolio = $this->portfolioService->create($request->validated(), Auth::user());
            return redirect()->route('portfolios.show', $portfolio)
                           ->with('success', 'Portafolio creado correctamente.');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'Error al crear el portafolio: ' . $e->getMessage());
        }
    }

    // Mostrar un portafolio específico
    public function show(Portfolio $portfolio)
    {
        return view('portfolio.show', compact('portfolio'));
    }

    // Mostrar formulario para editar un portafolio existente
    public function edit(Portfolio $portfolio)
    {
        $this->authorize('update', $portfolio);
        return view('portfolio.edit', compact('portfolio'));
    }

    // Actualizar los datos de un portafolio existente
    public function update(PortfolioRequest $request, Portfolio $portfolio)
    {
        try {
            $this->authorize('update', $portfolio);
            $portfolio = $this->portfolioService->update($portfolio, $request->validated());
            return redirect()->route('portfolios.show', $portfolio)
                           ->with('success', 'Portafolio actualizado correctamente.');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'Error al actualizar el portafolio: ' . $e->getMessage());
        }
    }

    // Eliminar un portafolio
    public function destroy(Portfolio $portfolio)
    {
        try {
            $this->authorize('delete', $portfolio);
            $this->portfolioService->delete($portfolio);
            return redirect()->route('portfolios.index')
                           ->with('success', 'Portafolio eliminado correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el portafolio: ' . $e->getMessage());
        }
    }
}
