<?php

namespace App\Http\Controllers;

use App\Models\JobOffer;
use App\Models\Category;
use App\Services\JobOfferService;
use App\Http\Requests\JobOfferRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class JobOfferController extends Controller
{
    protected $jobOfferService;

    public function __construct(JobOfferService $jobOfferService)
    {
        $this->jobOfferService = $jobOfferService;
    }

    // Mostrar listado de ofertas con filtros aplicables
    public function index(Request $request) {
        try {
            $filters = $request->only([
                'search',
                'location',
                'offer_type',
                'category',
                'salary_min',
                'salary_max',
                'company_id',
                'status',
                'per_page'
            ]);

            $jobOffers = $this->jobOfferService->getFilteredJobOffers($filters);
            $jobOffers->load(['company', 'categories', 'favoriteOffers']);
            $categories = Category::all();

            return view('job-offers.index', compact('jobOffers', 'categories'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar las ofertas: ' . $e->getMessage());
        }
    }

    // Guardar oferta (desde formulario personalizado)
    public function agg_job_offer(Request $request) {
        $offer = new JobOffer();
        $offer->company_id = $request->company_id;
        $offer->title = $request->title;
        $offer->description = $request->description;
        $offer->salary = $request->salary;
        $offer->location = $request->location;
        $offer->geolocation = $request->geolocation;
        $offer->offer_type = $request->offer_type;
        $offer->save();

        return $offer;
    }

    // Mostrar formulario de creación de oferta
    public function create()
    {
        $categories = Category::all();
        $offerType = request()->input('offer_type', JobOffer::TYPE_CONTRACT);
        return view('job-offers.create', compact('categories', 'offerType'));
    }

    // Guardar nueva oferta laboral
    public function store(JobOfferRequest $request)
    {
        try {
            $jobOffer = $this->jobOfferService->createJobOffer(
                $request->validated(),
                Auth::user()
            );

            $message = $jobOffer->offer_type === JobOffer::TYPE_CLASSIFIED
                ? 'Clasificado creado exitosamente.'
                : 'Oferta laboral creada exitosamente.';

            return redirect()
                ->route('job-offers.show', $jobOffer)
                ->with('success', $message);

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al crear la oferta: ' . $e->getMessage());
        }
    }

    // Mostrar detalles de una oferta
    public function show(JobOffer $jobOffer)
    {
        $jobOffer->load(['company', 'categories', 'comments.user']);
        $similarOffers = $this->jobOfferService->getSimilarJobOffers($jobOffer);
        $canApply = Auth::check() && Auth::user()->canApplyTo($jobOffer);

        return view('job-offers.show', compact('jobOffer', 'similarOffers', 'canApply'));
    }

    // Mostrar formulario de edición
    public function edit(JobOffer $jobOffer)
    {
        $categories = Category::all();
        return view('job-offers.edit', compact('jobOffer', 'categories'));
    }

    // Actualizar una oferta existente
    public function update(JobOfferRequest $request, JobOffer $jobOffer)
    {
        try {
            $this->jobOfferService->updateJobOffer($jobOffer, $request->validated());

            return redirect()
                ->route('job-offers.show', $jobOffer)
                ->with('success', 'Oferta actualizada exitosamente.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar la oferta: ' . $e->getMessage());
        }
    }

    // Eliminar una oferta laboral
    public function destroy(JobOffer $jobOffer)
    {
        try {
            $this->jobOfferService->deleteJobOffer($jobOffer);

            return redirect()
                ->route('job-offers.index')
                ->with('success', 'Oferta eliminada exitosamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar la oferta: ' . $e->getMessage());
        }
    }

    public function toggleStatus(JobOffer $jobOffer)
    {
        try {
            $success = $this->jobOfferService->toggleStatus($jobOffer);
            $status = $jobOffer->status ? 'activada' : 'desactivada';

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => "Oferta {$status} exitosamente.",
                    'status' => $jobOffer->status
                ]);
            }

            throw new \Exception('No se pudo cambiar el estado de la oferta.');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
