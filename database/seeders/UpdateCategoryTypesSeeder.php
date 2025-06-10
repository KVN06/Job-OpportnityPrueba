<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class UpdateCategoryTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Actualizar todas las categorías existentes que no tengan un tipo asignado
        Category::whereNull('type')->update(['type' => 'contract']);

        $this->command->info('Se han actualizado las categorías existentes con el tipo "contract"');
    }
}
