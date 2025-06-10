<?php

namespace App\Services;

use App\Models\JobApplication;
use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Exceptions\JobApplicationException;
use Illuminate\Support\Facades\Storage;

class JobApplicationService
{
    /**
     * Get filtered job applications
     */
    public function getFilteredApplications(array $filters = [], ?User $user = null)
    {
        $query = JobApplication::query()
            ->with(['jobOffer', 'unemployed.user']);

        if ($user) {
            if ($user->type === User::TYPE_UNEMPLOYED) {
                $query->whereHas('unemployed', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            } elseif ($user->type === User::TYPE_COMPANY) {
                $query->whereHas('jobOffer', function ($q) use ($user) {
                    $q->where('company_id', $user->company->id);
                });
            }
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['job_offer_id'])) {
            $query->where('job_offer_id', $filters['job_offer_id']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Create a new job application
     */
    public function apply(JobOffer $jobOffer, User $user, array $data): JobApplication
    {
        try {
            DB::beginTransaction();

            if ($user->type !== User::TYPE_UNEMPLOYED) {
                throw new JobApplicationException('Only unemployed users can apply for jobs');
            }

            if (!$user->unemployed) {
                throw new JobApplicationException('User must complete their unemployed profile first');
            }

            if ($jobOffer->status !== 'active') {
                throw new JobApplicationException('Cannot apply to inactive job offers');
            }

            // Check if user already applied
            if ($jobOffer->applications()->where('unemployed_id', $user->unemployed->id)->exists()) {
                throw new JobApplicationException('You have already applied to this job offer');
            }

            $application = new JobApplication([
                'unemployed_id' => $user->unemployed->id,
                'job_offer_id' => $jobOffer->id,
                'status' => 'pending',
                'cover_letter' => $data['cover_letter'] ?? null,
                'expected_salary' => $data['expected_salary'] ?? null,
            ]);

            if (isset($data['resume']) && $data['resume']) {
                $application->resume_path = $this->uploadResume($data['resume']);
            }

            $application->save();

            // Notify company about new application
            if ($jobOffer->company->user) {
                // You can implement notification logic here
            }

            DB::commit();
            return $application;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new JobApplicationException('Error applying to job: ' . $e->getMessage());
        }
    }

    /**
     * Update application status
     */
    public function updateStatus(JobApplication $application, string $status, ?string $feedback = null): JobApplication
    {
        try {
            DB::beginTransaction();

            $application->update([
                'status' => $status,
                'feedback' => $feedback,
                'processed_at' => now(),
            ]);

            // Notify applicant about status change
            if ($application->unemployed->user) {
                // You can implement notification logic here
            }

            DB::commit();
            return $application;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new JobApplicationException('Error updating application status: ' . $e->getMessage());
        }
    }

    /**
     * Withdraw application
     */
    public function withdraw(JobApplication $application): bool
    {
        try {
            DB::beginTransaction();

            if ($application->status !== 'pending') {
                throw new JobApplicationException('Can only withdraw pending applications');
            }

            $result = $application->delete();

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new JobApplicationException('Error withdrawing application: ' . $e->getMessage());
        }
    }

    /**
     * Upload resume file
     */
    protected function uploadResume($file): string
    {
        return $file->store('applications/resumes', 'public');
    }

    /**
     * Delete resume file
     */
    protected function deleteResume(?string $path): bool
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }
}
