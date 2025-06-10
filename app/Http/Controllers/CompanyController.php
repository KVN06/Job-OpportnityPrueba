<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Services\CompanyService;
use App\Http\Requests\CompanyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    protected $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->only([
                'search',
                'location',
                'industry',
                'per_page'
            ]);

            $companies = $this->companyService->getFilteredCompanies($filters);
            return view('companies.index', compact('companies'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar las empresas: ' . $e->getMessage());
        }
    }

    public function create()
    {
        \Log::info('CompanyController@create accedido', [
            'user_id' => Auth::id(),
            'user_type' => Auth::user()->type,
            'user_name' => Auth::user()->name
        ]);
        
        return view('companies.create');
    }

    public function store(CompanyRequest $request)
    {
        \Log::info('CompanyController@store iniciado', [
            'user_id' => Auth::id(),
            'data' => $request->validated()
        ]);
        
        try {
            $company = $this->companyService->create($request->validated(), Auth::user());
            
            \Log::info('Empresa creada exitosamente', [
                'company_id' => $company->id,
                'user_id' => Auth::id()
            ]);
            
            return redirect()->route('dashboard')
                           ->with('success', '¡Perfil de empresa completado exitosamente! Ahora puedes acceder a todas las funcionalidades: publicar ofertas de trabajo, gestionar candidatos y mucho más.');
        } catch (\Exception $e) {
            \Log::error('Error al crear empresa', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withInput()
                        ->with('error', 'Error al crear la empresa: ' . $e->getMessage());
        }
    }

    public function show(Company $company)
    {
        $company->load(['jobOffers' => function($query) {
            $query->latest();
        }]);
        return view('companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(CompanyRequest $request, Company $company)
    {
        try {
            $company = $this->companyService->update($company, $request->validated());
            return redirect()->route('companies.show', $company)
                           ->with('success', 'Empresa actualizada correctamente.');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'Error al actualizar la empresa: ' . $e->getMessage());
        }
    }

    public function destroy(Company $company)
    {
        try {
            $this->companyService->delete($company);
            return redirect()->route('companies.index')
                           ->with('success', 'Empresa eliminada correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la empresa: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            $filters = $request->only(['search', 'location', 'industry']);
            $companies = $this->companyService->getFilteredCompanies($filters);
            return view('companies.search', compact('companies'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error en la búsqueda: ' . $e->getMessage());
        }
    }
}
