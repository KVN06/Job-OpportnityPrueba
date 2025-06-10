<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\CategoryService;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->only(['search', 'type', 'per_page']);
            $categories = $this->categoryService->getFilteredCategories($filters);
            return view('categories.index', compact('categories'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar las categorías: ' . $e->getMessage());
        }
    }

    public function store(CategoryRequest $request)
    {
        try {
            $category = $this->categoryService->create($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Categoría creada correctamente',
                'category' => $category,
                'id' => $category->id,
                'name' => $category->name
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la categoría: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(CategoryRequest $request, Category $category)
    {
        try {
            $category = $this->categoryService->update($category, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Categoría actualizada correctamente',
                'category' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la categoría: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Category $category)
    {
        try {
            $this->categoryService->delete($category);
            return response()->json([
                'success' => true,
                'message' => 'Categoría eliminada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la categoría: ' . $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');
            $type = $request->get('type');
            
            // First try exact matches
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

            // If no exact matches, try finding similar ones
            $suggestions = [];
            if ($categories->isEmpty() && strlen($query) > 2) {
                $suggestions = $this->categoryService->searchSimilar($query, $type);
            }

            return response()->json([
                'results' => $categories,
                'suggestions' => $suggestions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la búsqueda: ' . $e->getMessage()
            ], 500);
        }
    }
}
