<?php

namespace App\Services;

use App\Models\FavoriteOffer;
use App\Models\JobOffer;
use App\Models\User;

class FavoriteOfferService
{
    /**
     * Get favorite offers for a user with filters.
     */
    public function getFavoriteOffers(User $user, array $filters = [])
    {
        $query = JobOffer::whereHas('favoriteOffers', function($query) use ($user) {
            $query->where('unemployed_id', $user->unemployed->id);
        })->with(['company', 'categories', 'favoriteOffers']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['category'])) {
            $query->whereHas('categories', function($q) use ($filters) {
                $q->where('categories.id', $filters['category']);
            });
        }

        if (!empty($filters['salary_min'])) {
            $query->where('salary', '>=', $filters['salary_min']);
        }

        if (!empty($filters['salary_max'])) {
            $query->where('salary', '<=', $filters['salary_max']);
        }

        if (!empty($filters['location'])) {
            $query->where('location', 'like', "%{$filters['location']}%");
        }

        return $query->latest()->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Toggle favorite status for a job offer.
     */
    public function toggle(JobOffer $jobOffer, User $user, array $data = [])
    {
        $exists = FavoriteOffer::where('unemployed_id', $user->unemployed->id)
                              ->where('job_offer_id', $jobOffer->id)
                              ->exists();

        if ($exists) {
            FavoriteOffer::where('unemployed_id', $user->unemployed->id)
                        ->where('job_offer_id', $jobOffer->id)
                        ->delete();
            return [
                'isFavorite' => false,
                'message' => 'Oferta eliminada de favoritos'
            ];
        }

        FavoriteOffer::create([
            'unemployed_id' => $user->unemployed->id,
            'job_offer_id' => $jobOffer->id,
            'notes' => $data['notes'] ?? null,
            'notification_preferences' => $data['notification_preferences'] ?? FavoriteOffer::DEFAULT_NOTIFICATIONS
        ]);

        return [
            'isFavorite' => true,
            'message' => 'Oferta añadida a favoritos'
        ];
    }

    /**
     * Update notification preferences for a favorite offer.
     */
    public function updatePreferences(FavoriteOffer $favorite, array $preferences)
    {
        $favorite->notification_preferences = array_merge(
            FavoriteOffer::DEFAULT_NOTIFICATIONS,
            $preferences
        );
        $favorite->save();

        return $favorite;
    }

    /**
     * Get similar offers based on a favorite offer.
     */
    public function getSimilarOffers(FavoriteOffer $favorite, $limit = 5)
    {
        return JobOffer::where('id', '!=', $favorite->job_offer_id)
            ->where('status', true)
            ->whereHas('categories', function($query) use ($favorite) {
                $query->whereIn('categories.id', $favorite->jobOffer->categories->pluck('id'));
            })
            ->where(function($query) use ($favorite) {
                $query->whereBetween('salary', [
                    $favorite->jobOffer->salary * 0.8,
                    $favorite->jobOffer->salary * 1.2
                ])
                ->orWhere('location', 'like', "%{$favorite->jobOffer->location}%");
            })
            ->latest()
            ->limit($limit)
            ->get();
    }
}
