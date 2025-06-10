<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Services\TrainingService;
use App\Http\Requests\TrainingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingController extends Controller
{
    protected $trainingService;

    public function __construct(TrainingService $trainingService)
    {
        $this->middleware('auth');
        $this->middleware('role:company,admin')->except(['index', 'show', 'enroll', 'complete']);
        $this->trainingService = $trainingService;
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->only([
                'search',
                'type',
                'level',
                'status',
                'price_min',
                'price_max',
                'start_date',
                'end_date',
                'company_id',
                'per_page'
            ]);

            $trainings = $this->trainingService->getFilteredTrainings($filters);
            return view('trainings.index', compact('trainings'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar las capacitaciones: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('trainings.create');
    }

    public function store(TrainingRequest $request)
    {
        try {
            $training = $this->trainingService->create($request->validated());
            return redirect()->route('trainings.show', $training)
                           ->with('success', 'Capacitación creada correctamente.');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'Error al crear la capacitación: ' . $e->getMessage());
        }
    }

    public function show(Training $training)
    {
        $training->load(['company', 'users']);
        $isEnrolled = Auth::check() ? $training->users()->where('user_id', Auth::id())->exists() : false;
        return view('trainings.show', compact('training', 'isEnrolled'));
    }

    public function edit(Training $training)
    {
        $this->authorize('update', $training);
        return view('trainings.edit', compact('training'));
    }

    public function update(TrainingRequest $request, Training $training)
    {
        try {
            $this->authorize('update', $training);
            $training = $this->trainingService->update($training, $request->validated());
            return redirect()->route('trainings.show', $training)
                           ->with('success', 'Capacitación actualizada correctamente.');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'Error al actualizar la capacitación: ' . $e->getMessage());
        }
    }

    public function destroy(Training $training)
    {
        try {
            $this->authorize('delete', $training);
            $this->trainingService->delete($training);
            return redirect()->route('trainings.index')
                           ->with('success', 'Capacitación eliminada correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la capacitación: ' . $e->getMessage());
        }
    }

    public function enroll(Training $training)
    {
        try {
            $this->middleware('role:unemployed');
            $this->trainingService->enrollUser($training, Auth::user());
            return redirect()->route('trainings.show', $training)
                           ->with('success', 'Te has inscrito correctamente en la capacitación.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al inscribirse en la capacitación: ' . $e->getMessage());
        }
    }

    public function complete(Training $training)
    {
        try {
            $this->middleware('role:unemployed');
            $this->trainingService->completeTraining($training, Auth::user());
            return redirect()->route('trainings.show', $training)
                           ->with('success', 'Has completado la capacitación correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al marcar la capacitación como completada: ' . $e->getMessage());
        }
    }
}
