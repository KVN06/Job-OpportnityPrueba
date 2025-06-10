<?php

namespace App\Services;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use App\Exceptions\CompanyException;

class CompanyService
{
    /**
     * Get filtered companies
     */
    public function getFilteredCompanies(array $filters = [])
    {
        $query = Company::query()->with(['user', 'jobOffers']);

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('description', 'like', "%{$filters['search']}%")
                  ->orWhere('location', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['location'])) {
            $query->where('location', 'like', "%{$filters['location']}%");
        }

        if (!empty($filters['industry'])) {
            $query->where('industry', $filters['industry']);
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Create a new company
     */
    public function create(array $data, User $user): Company
    {
        \Log::info('CompanyService@create iniciado', [
            'user_id' => $user->id,
            'data' => $data
        ]);
        
        try {
            DB::beginTransaction();

            $company = new Company($data);
            $company->user()->associate($user);

            if (isset($data['logo']) && $data['logo']) {
                $company->logo = $this->uploadLogo($data['logo']);
            }

            \Log::info('Guardando empresa en base de datos');
            $company->save();
            \Log::info('Empresa guardada exitosamente', ['company_id' => $company->id]);

            DB::commit();
            return $company;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error en CompanyService@create', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new CompanyException('Error creating company: ' . $e->getMessage());
        }
    }

    /**
     * Update company details
     */
    public function update(Company $company, array $data): Company
    {
        try {
            DB::beginTransaction();

            if (isset($data['logo']) && $data['logo']) {
                $this->deleteLogo($company->logo);
                $data['logo'] = $this->uploadLogo($data['logo']);
            }

            $company->update($data);

            DB::commit();
            return $company;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new CompanyException('Error updating company: ' . $e->getMessage());
        }
    }

    /**
     * Delete a company
     */
    public function delete(Company $company): bool
    {
        try {
            DB::beginTransaction();

            if ($company->logo) {
                $this->deleteLogo($company->logo);
            }

            $result = $company->delete();

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new CompanyException('Error deleting company: ' . $e->getMessage());
        }
    }

    /**
     * Upload company logo
     */
    protected function uploadLogo($file): string
    {
        return $file->store('companies/logos', 'public');
    }

    /**
     * Delete company logo
     */
    protected function deleteLogo(?string $path): bool
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }
}
