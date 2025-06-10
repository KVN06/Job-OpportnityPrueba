<?php

namespace App\Services;

use App\Models\Training;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Exceptions\TrainingException;
use Illuminate\Support\Facades\Storage;

class TrainingService
{
    /**
     * Get filtered trainings
     */
    public function getFilteredTrainings(array $filters = [])
    {
        $query = Training::query()->with(['company', 'users']);

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['search']}%")
                  ->orWhere('description', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['company_id'])) {
            $query->where('company_id', $filters['company_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['start_date'])) {
            $query->where('start_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('end_date', '<=', $filters['end_date']);
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Create a new training
     */
    public function create(array $data): Training
    {
        try {
            DB::beginTransaction();

            $training = Training::create($data);

            if (isset($data['materials']) && $data['materials']) {
                foreach ($data['materials'] as $material) {
                    $path = $this->uploadMaterial($material);
                    $training->materials()->create(['path' => $path]);
                }
            }

            DB::commit();
            return $training;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new TrainingException('Error creating training: ' . $e->getMessage());
        }
    }

    /**
     * Update training
     */
    public function update(Training $training, array $data): Training
    {
        try {
            DB::beginTransaction();

            $training->update($data);

            if (isset($data['materials']) && $data['materials']) {
                // Delete old materials
                foreach ($training->materials as $material) {
                    $this->deleteMaterial($material->path);
                }
                $training->materials()->delete();

                // Upload new materials
                foreach ($data['materials'] as $material) {
                    $path = $this->uploadMaterial($material);
                    $training->materials()->create(['path' => $path]);
                }
            }

            DB::commit();
            return $training;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new TrainingException('Error updating training: ' . $e->getMessage());
        }
    }

    /**
     * Delete training
     */
    public function delete(Training $training): bool
    {
        try {
            DB::beginTransaction();

            // Delete all materials
            foreach ($training->materials as $material) {
                $this->deleteMaterial($material->path);
            }

            $result = $training->delete();

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new TrainingException('Error deleting training: ' . $e->getMessage());
        }
    }

    /**
     * Enroll user in training
     */
    public function enrollUser(Training $training, User $user): bool
    {
        try {
            DB::beginTransaction();

            if ($training->users()->where('user_id', $user->id)->exists()) {
                throw new TrainingException('User already enrolled in this training');
            }

            $training->users()->attach($user->id, [
                'status' => 'enrolled',
                'enrolled_at' => now()
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new TrainingException('Error enrolling in training: ' . $e->getMessage());
        }
    }

    /**
     * Complete training for user
     */
    public function completeTraining(Training $training, User $user): bool
    {
        try {
            DB::beginTransaction();

            $enrollment = $training->users()->where('user_id', $user->id)->first();
            
            if (!$enrollment) {
                throw new TrainingException('User not enrolled in this training');
            }

            $training->users()->updateExistingPivot($user->id, [
                'status' => 'completed',
                'completed_at' => now()
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new TrainingException('Error completing training: ' . $e->getMessage());
        }
    }

    /**
     * Upload training material
     */
    protected function uploadMaterial($file): string
    {
        return $file->store('trainings/materials', 'public');
    }

    /**
     * Delete training material
     */
    protected function deleteMaterial(?string $path): bool
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }
}
