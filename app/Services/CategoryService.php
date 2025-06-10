<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Exceptions\CategoryException;
use Illuminate\Support\Str;

class CategoryService
{
    /**
     * Get filtered categories
     */
    public function getFilteredCategories(array $filters = [])
    {
        $query = Category::query()->withCount('jobOffers');

        if (!empty($filters['search'])) {
            $query->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('description', 'like', "%{$filters['search']}%");
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query->orderBy('name')->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Create a new category
     */
    public function create(array $data): Category
    {
        try {
            DB::beginTransaction();

            // Set default type if not provided
            if (!isset($data['type'])) {
                $data['type'] = 'contract';
            }

            $data['slug'] = Str::slug($data['name']);
            $category = Category::create($data);

            DB::commit();
            return $category;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new CategoryException('Error creating category: ' . $e->getMessage());
        }
    }

    /**
     * Update category
     */
    public function update(Category $category, array $data): Category
    {
        try {
            DB::beginTransaction();

            if (isset($data['name'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            $category->update($data);

            DB::commit();
            return $category;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new CategoryException('Error updating category: ' . $e->getMessage());
        }
    }

    /**
     * Delete category
     */
    public function delete(Category $category): bool
    {
        try {
            DB::beginTransaction();

            // Check if category has job offers
            if ($category->jobOffers()->exists()) {
                throw new CategoryException('Cannot delete category with associated job offers');
            }

            $result = $category->delete();

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new CategoryException('Error deleting category: ' . $e->getMessage());
        }
    }

    /**
     * Search for similar categories
     */
    public function searchSimilar(string $query, string $type = null): array
    {
        $categories = Category::where('name', 'like', "%{$query}%")
                            ->when($type, function($q) use ($type) {
                                return $q->where('type', $type);
                            })
                            ->limit(5)
                            ->get()
                            ->map(function($category) {
                                return [
                                    'id' => $category->id,
                                    'name' => $category->name,
                                    'text' => $category->name
                                ];
                            });

        return $categories->toArray();
    }
}
