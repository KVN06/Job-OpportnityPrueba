<?php

namespace App\Services;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class PortfolioService
{
    /**
     * Get filtered portfolios.
     */
    public function getFilteredPortfolios(array $filters, User $user)
    {
        $query = Portfolio::query()
            ->where('unemployed_id', $user->unemployed->id)
            ->with(['unemployed', 'comments']);

        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['search']}%")
                  ->orWhere('description', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['technologies'])) {
            $query->where('technologies', 'like', "%{$filters['technologies']}%");
        }

        return $query->latest()->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Create a new portfolio.
     */
    public function create(array $data, User $user)
    {
        $portfolio = new Portfolio();
        $portfolio->unemployed_id = $user->unemployed->id;
        $portfolio->title = $data['title'];
        $portfolio->description = $data['description'];
        $portfolio->url_proyect = $data['url_proyect'];
        $portfolio->status = $data['status'] ?? Portfolio::STATUS_DRAFT;
        $portfolio->technologies = $data['technologies'] ?? [];

        if (isset($data['url_pdf']) && $data['url_pdf'] instanceof UploadedFile) {
            $portfolio->url_pdf = $this->storeFile($data['url_pdf']);
        }

        $portfolio->save();

        return $portfolio;
    }

    /**
     * Update an existing portfolio.
     */
    public function update(Portfolio $portfolio, array $data)
    {
        $portfolio->title = $data['title'];
        $portfolio->description = $data['description'];
        $portfolio->url_proyect = $data['url_proyect'];
        
        if (isset($data['status'])) {
            $portfolio->status = $data['status'];
        }

        if (isset($data['technologies'])) {
            $portfolio->technologies = $data['technologies'];
        }

        if (isset($data['url_pdf']) && $data['url_pdf'] instanceof UploadedFile) {
            // Delete old file if exists
            if ($portfolio->url_pdf) {
                Storage::delete('public/portfolios/' . $portfolio->url_pdf);
            }
            $portfolio->url_pdf = $this->storeFile($data['url_pdf']);
        }

        $portfolio->save();

        return $portfolio;
    }

    /**
     * Delete a portfolio and its associated files.
     */
    public function delete(Portfolio $portfolio)
    {
        if ($portfolio->url_pdf) {
            Storage::delete('public/portfolios/' . $portfolio->url_pdf);
        }

        return $portfolio->delete();
    }

    /**
     * Store a file in storage.
     */
    protected function storeFile(UploadedFile $file)
    {
        $filename = 'pdf_' . Str::random(10) . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/portfolios', $filename);
        return $filename;
    }
}
