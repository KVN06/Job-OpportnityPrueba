<?php

namespace App\Http\Controllers;

use App\Models\Unemployed;
use App\Services\UnemployedService;
use App\Http\Requests\UnemployedRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnemployedController extends Controller
{
    protected $unemployedService;

    public function __construct(UnemployedService $unemployedService)
    {
        $this->middleware('auth');
        $this->middleware('role:unemployed')->except(['show']);
        $this->unemployedService = $unemployedService;
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->only([
                'search',
                'location',
                'profession',
                'experience_years',
                'per_page'
            ]);

            $unemployedProfiles = $this->unemployedService->getFilteredProfiles($filters);
            return view('unemployed.index', compact('unemployedProfiles'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar los perfiles: ' . $e->getMessage());
        }
    }

    public function create()
    {
        if (Auth::user()->unemployed()->exists()) {
            return redirect()->route('unemployed.edit', Auth::user()->unemployed)
                           ->with('info', 'Ya tienes un perfil creado. Puedes editarlo aquí.');
        }
        return view('unemployed.create');
    }

    public function store(UnemployedRequest $request)
    {
        try {
            $unemployed = $this->unemployedService->create($request->validated(), Auth::user());
            return redirect()->route('unemployed.show', $unemployed)
                           ->with('success', 'Perfil creado correctamente.');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'Error al crear el perfil: ' . $e->getMessage());
        }
    }

    public function show(Unemployed $unemployed)
    {
        $unemployed->load(['user', 'portfolio', 'favoriteOffers', 'jobApplications']);
        return view('unemployed.show', compact('unemployed'));
    }

    public function edit(Unemployed $unemployed)
    {
        $this->authorize('update', $unemployed);
        return view('unemployed.edit', compact('unemployed'));
    }

    public function update(UnemployedRequest $request, Unemployed $unemployed)
    {
        try {
            $this->authorize('update', $unemployed);
            $unemployed = $this->unemployedService->update($unemployed, $request->validated());
            return redirect()->route('unemployed.show', $unemployed)
                           ->with('success', 'Perfil actualizado correctamente.');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'Error al actualizar el perfil: ' . $e->getMessage());
        }
    }

    public function destroy(Unemployed $unemployed)
    {
        try {
            $this->authorize('delete', $unemployed);
            $this->unemployedService->delete($unemployed);
            return redirect()->route('dashboard')
                           ->with('success', 'Perfil eliminado correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el perfil: ' . $e->getMessage());
        }
    }
}