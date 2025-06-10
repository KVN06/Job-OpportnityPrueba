<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobOffer;
use App\Services\JobApplicationService;
use App\Http\Requests\JobApplicationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    protected $jobApplicationService;

    public function __construct(JobApplicationService $jobApplicationService)
    {
        $this->middleware('auth');
        $this->jobApplicationService = $jobApplicationService;
    }

    /**
     * Display a listing of the applications.
     */
    public function index(Request $request)
    {
        try {
            $filters = $request->only([
                'status',
                'job_offer_id',
                'date_from',
                'date_to',
                'per_page'
            ]);

            $applications = $this->jobApplicationService->getFilteredApplications($filters, Auth::user());
            return view('applications.index', compact('applications'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar las aplicaciones: ' . $e->getMessage());
        }
    }

    /**
     * Show application form for a job offer.
     */
    public function create(JobOffer $jobOffer)
    {
        if (Auth::user()->type !== 'unemployed') {
            return redirect()->route('job-offers.show', $jobOffer)
                           ->with('error', 'Solo los usuarios desempleados pueden postular.');
        }

        return view('applications.create', compact('jobOffer'));
    }

    /**
     * Store a new application.
     */
    public function store(JobApplicationRequest $request)
    {
        try {
            $jobOffer = JobOffer::findOrFail($request->job_offer_id);
            $application = $this->jobApplicationService->apply($jobOffer, Auth::user(), $request->validated());
            return view('job-offers.applied');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', 'Error al enviar la postulación: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified application.
     */
    public function show(JobApplication $application)
    {
        $this->authorize('view', $application);
        return view('applications.show', compact('application'));
    }

    /**
     * Update application status.
     */
    public function updateStatus(JobApplicationRequest $request, JobApplication $application)
    {
        try {
            $this->authorize('update', $application);
            $application = $this->jobApplicationService->updateStatus(
                $application,
                $request->status,
                $request->feedback
            );
            
            return redirect()->route('applications.show', $application)
                           ->with('success', 'Estado de la postulación actualizado correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el estado: ' . $e->getMessage());
        }
    }

    /**
     * Withdraw application.
     */
    public function withdraw(JobApplication $application)
    {
        try {
            $this->authorize('delete', $application);
            $this->jobApplicationService->withdraw($application);
            return redirect()->route('applications.index')
                           ->with('success', 'Postulación retirada correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al retirar la postulación: ' . $e->getMessage());
        }
    }
}
