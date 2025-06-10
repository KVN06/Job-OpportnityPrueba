<?php

namespace App\Services;

use App\Models\Unemployed;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\UnemployedException;

class UnemployedService
{
    /**
     * Get filtered unemployed profiles
     */
    public function getFilteredProfiles(array $filters = [])
    {
        $query = Unemployed::query()->with(['user', 'jobApplications', 'portfolio']);

        if (!empty($filters['search'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%");
            })->orWhere('skills', 'like', "%{$filters['search']}%")
              ->orWhere('experience', 'like', "%{$filters['search']}%");
        }

        if (!empty($filters['skills'])) {
            $query->where('skills', 'like', "%{$filters['skills']}%");
        }

        if (!empty($filters['experience_level'])) {
            $query->where('experience_level', $filters['experience_level']);
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Create unemployed profile
     */
    public function create(array $data, User $user): Unemployed
    {
        try {
            DB::beginTransaction();

            $unemployed = new Unemployed($data);
            $unemployed->user()->associate($user);

            if (isset($data['cv']) && $data['cv']) {
                $unemployed->cv = $this->uploadCV($data['cv']);
            }

            $unemployed->save();

            DB::commit();
            return $unemployed;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new UnemployedException('Error creating profile: ' . $e->getMessage());
        }
    }

    /**
     * Update unemployed profile
     */
    public function update(Unemployed $unemployed, array $data): Unemployed
    {
        try {
            DB::beginTransaction();

            if (isset($data['cv']) && $data['cv']) {
                $this->deleteCV($unemployed->cv);
                $data['cv'] = $this->uploadCV($data['cv']);
            }

            $unemployed->update($data);

            DB::commit();
            return $unemployed;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new UnemployedException('Error updating profile: ' . $e->getMessage());
        }
    }

    /**
     * Delete unemployed profile
     */
    public function delete(Unemployed $unemployed): bool
    {
        try {
            DB::beginTransaction();

            if ($unemployed->cv) {
                $this->deleteCV($unemployed->cv);
            }

            $result = $unemployed->delete();

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new UnemployedException('Error deleting profile: ' . $e->getMessage());
        }
    }

    /**
     * Upload CV file
     */
    protected function uploadCV($file): string
    {
        return $file->store('unemployed/cvs', 'public');
    }

    /**
     * Delete CV file
     */
    protected function deleteCV(?string $path): bool
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }
}
