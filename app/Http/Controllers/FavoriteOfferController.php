<?php

namespace App\Http\Controllers;

use App\Models\FavoriteOffer;
use App\Models\JobOffer;
use App\Services\FavoriteOfferService;
use App\Http\Requests\FavoriteOfferRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteOfferController extends Controller
{
    protected $favoriteOfferService;

    public function __construct(FavoriteOfferService $favoriteOfferService)
    {
        $this->middleware('auth');
        $this->middleware('role:unemployed');
        $this->favoriteOfferService = $favoriteOfferService;
    }

    // Mostrar las ofertas favoritas del usuario cesante autenticado
    public function index(Request $request)
    {
        try {
            $filters = $request->only([
                'status',
                'category',
                'salary_min',
                'salary_max',
                'location',
                'per_page'
            ]);

            $favoriteOffers = $this->favoriteOfferService->getFavoriteOffers(Auth::user(), $filters);
            return view('job-offers.favorites', compact('favoriteOffers'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar las ofertas favoritas: ' . $e->getMessage());
        }
    }

    // Agregar o quitar una oferta de los favoritos del usuario
    public function toggle(FavoriteOfferRequest $request, JobOffer $jobOffer)
    {
        try {
            $result = $this->favoriteOfferService->toggle($jobOffer, Auth::user(), $request->validated());
            
            return response()->json([
                'success' => true,
                'isFavorite' => $result['isFavorite'],
                'message' => $result['message']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar favoritos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePreferences(FavoriteOfferRequest $request, FavoriteOffer $favorite)
    {
        try {
            $this->authorize('update', $favorite);
            
            $favorite = $this->favoriteOfferService->updatePreferences(
                $favorite,
                $request->validated()['notification_preferences'] ?? []
            );

            return response()->json([
                'success' => true,
                'message' => 'Preferencias actualizadas correctamente',
                'preferences' => $favorite->notification_preferences
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar preferencias: ' . $e->getMessage()
            ], 500);
        }
    }
}
