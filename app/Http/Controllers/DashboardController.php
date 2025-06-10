<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JobOffer;
use App\Models\Training;
use App\Models\Company;
use App\Models\Unemployed;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Middleware is handled in routes
    }

    /**
     * Display the appropriate dashboard based on user type
     */
    public function index()
    {
        $user = Auth::user();
        
        // Para simplificar, usar dashboard genérico para todos
        return view('dashboard.generic');
        
        /*
        switch ($user->type) {
            case 'admin':
                return $this->adminDashboard();
            case 'company':
                return $this->companyDashboard();
            case 'unemployed':
                return $this->unemployedDashboard();
            default:
                return redirect()->route('login');
        }
        */
    }

    /**
     * Admin dashboard showing system overview
     */
    protected function adminDashboard()
    {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_companies' => Company::count(),
            'total_unemployed' => Unemployed::count(),
            'total_job_offers' => JobOffer::count(),
            'active_job_offers' => JobOffer::where('status', true)->count(),
            'total_trainings' => Training::count(),
            'recent_activities' => \App\Models\User::latest()->take(5)->get()
        ];

        return view('dashboard.admin', compact('stats'));
    }

    /**
     * Company dashboard showing their job offers and applications
     */
    protected function companyDashboard()
    {
        $user = Auth::user();
        $company = $user->company;
        
        // Si el usuario no tiene un perfil de empresa, redirigir a crear uno
        if (!$company) {
            return redirect()->route('companies.create')
                           ->with('info', 'Necesitas completar tu perfil de empresa para acceder al dashboard.');
        }
        
        $data = [
            'active_job_offers' => JobOffer::where('company_id', $company->id)
                                         ->where('status', true)
                                         ->latest()
                                         ->take(5)
                                         ->get(),
            'recent_applications' => JobOffer::where('company_id', $company->id)
                                          ->with(['jobApplications' => function($q) {
                                              $q->latest()->take(5);
                                          }])
                                          ->get(),
            'trainings' => Training::where('company_id', $company->id)
                                  ->latest()
                                  ->take(5)
                                  ->get(),
            'stats' => [
                'total_job_offers' => JobOffer::where('company_id', $company->id)->count(),
                'active_job_offers' => JobOffer::where('company_id', $company->id)->where('status', true)->count(),
                'total_applications' => $company->jobOffers()->withCount('jobApplications')->get()->sum('job_applications_count'),
                'total_trainings' => Training::where('company_id', $company->id)->count()
            ]
        ];

        return view('dashboard.company', compact('data'));
    }

    /**
     * Unemployed dashboard showing job recommendations and application status
     */
    protected function unemployedDashboard()
    {
        $user = Auth::user();
        $unemployed = $user->unemployed;
        
        // Si el usuario no tiene un perfil de desempleado, redirigir a crear uno
        if (!$unemployed) {
            return redirect()->route('unemployed.create')
                           ->with('info', 'Necesitas completar tu perfil para acceder al dashboard.');
        }
        
        $data = [
            'recent_applications' => $unemployed->jobApplications()
                                             ->with('jobOffer.company')
                                             ->latest()
                                             ->take(5)
                                             ->get(),
            'recommended_jobs' => JobOffer::where('status', true)
                                       ->latest()
                                       ->take(5)
                                       ->get(),
            'favorite_offers' => $unemployed->favoriteOffers()
                                          ->latest()
                                          ->take(5)
                                          ->get(),
            'available_trainings' => Training::latest()
                                           ->take(5)
                                           ->get(),
            'stats' => [
                'total_applications' => $unemployed->jobApplications()->count(),
                'pending_applications' => $unemployed->jobApplications()->where('status', 'pending')->count(),
                'favorite_offers' => $unemployed->favoriteOffers()->count(),
                'completed_trainings' => 0 // Simplificado por ahora
            ]
        ];

        return view('dashboard.unemployed', compact('data'));
    }
}
