<?php

namespace App\Services;

use App\Models\JobOffer;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class JobOfferService
{
    public function getFilteredJobOffers(array $filters): LengthAwarePaginator
    {
        $query = JobOffer::with(['company', 'categories', 'favoriteUnemployed']);

        if (isset($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['search']}%")
                  ->orWhere('description', 'like', "%{$filters['search']}%");
            });
        }

        if (isset($filters['location'])) {
            $query->where('location', $filters['location']);
        }

        if (isset($filters['offer_type'])) {
            $query->where('offer_type', $filters['offer_type']);
        }

        if (isset($filters['category'])) {
            $query->whereHas('categories', function($q) use ($filters) {
                $q->where('categories.id', $filters['category']);
            });
        }

        if (isset($filters['salary_min'])) {
            $query->where('salary', '>=', $filters['salary_min']);
        }

        if (isset($filters['salary_max'])) {
            $query->where('salary', '<=', $filters['salary_max']);
        }

        if (isset($filters['company_id'])) {
            $query->where('company_id', $filters['company_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate($filters['per_page'] ?? 10);
    }

    public function createJobOffer(array $data, User $user): JobOffer
    {
        // Verificar que el usuario tenga una empresa asociada
        if (!$user->company) {
            throw new \Exception('Debes completar el perfil de tu empresa antes de crear ofertas laborales.');
        }

        // Asignar el company_id
        $data['company_id'] = $user->company->id;

        // Crear la oferta laboral
        $jobOffer = JobOffer::create($data);

        // Asociar categorías si existen
        if (isset($data['categories']) && is_array($data['categories'])) {
            $jobOffer->categories()->attach($data['categories']);
        }

        // Notificar a usuarios relevantes para ofertas de contrato
        if ($jobOffer->offer_type === JobOffer::TYPE_CONTRACT) {
            $this->notifyRelevantUsers($jobOffer);
        }

        return $jobOffer;
    }

    public function updateJobOffer(JobOffer $jobOffer, array $data): JobOffer
    {
        $jobOffer->update($data);

        if (isset($data['categories'])) {
            $jobOffer->categories()->sync($data['categories']);
        }

        return $jobOffer;
    }

    public function toggleStatus(JobOffer $jobOffer): bool
    {
        $jobOffer->status = !$jobOffer->status;
        return $jobOffer->save();
    }

    public function deleteJobOffer(JobOffer $jobOffer): bool
    {
        // Notify applicants about deletion
        $jobOffer->jobApplications->each(function ($application) {
            // Send notification to applicant
            // TODO: Implement JobOfferDeletedNotification
            // $application->unemployed->user->notify(
            //     new JobOfferDeletedNotification($application->jobOffer)
            // );
        });

        return $jobOffer->delete();
    }

    public function getSimilarJobOffers(JobOffer $jobOffer, int $limit = 5): Collection
    {
        return JobOffer::query()
            ->where('id', '!=', $jobOffer->id)
            ->where('status', true)
            ->where(function ($query) use ($jobOffer) {
                $query->whereHas('categories', function ($q) use ($jobOffer) {
                    $q->whereIn('categories.id', $jobOffer->categories->pluck('id'));
                })
                ->orWhere('location', $jobOffer->location)
                ->orWhereBetween('salary', [
                    $jobOffer->salary * 0.8,
                    $jobOffer->salary * 1.2
                ]);
            })
            ->latest()
            ->limit($limit)
            ->get();
    }

    protected function notifyRelevantUsers(JobOffer $jobOffer): void
    {
        // Find users with matching preferences
        $matchingUsers = User::query()
            ->whereHas('preference', function ($query) use ($jobOffer) {
                $query->whereJsonContains('job_preferences->categories', $jobOffer->categories->pluck('id'))
                    ->orWhereJsonContains('job_preferences->locations', $jobOffer->location);
            })
            ->get();

        // Notify each matching user
        $matchingUsers->each(function ($user) use ($jobOffer) {
            // TODO: Implement NewMatchingJobOfferNotification
            // $user->notify(new NewMatchingJobOfferNotification($jobOffer));
        });
    }
}
