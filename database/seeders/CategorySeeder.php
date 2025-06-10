<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Desarrollo de Software',
            'Marketing Digital',
            'Diseño Gráfico',
            'Ventas',
            'Atención al Cliente',
            'Recursos Humanos',
            'Administración',
            'Finanzas',
            'Contabilidad',
            'Ingeniería',
            'Educación',
            'Salud',
            'Legal',
            'Logística',
            'Manufactura',
            'Construcción',
            'Tecnología de la Información',
            'Comercio',
            'Comunicación',
            'Traducción'
        ];

        foreach ($categories as $categoryName) {
            Category::firstOrCreate(
                ['name' => $categoryName],
                ['slug' => \Str::slug($categoryName)]
            );
        }
    }
}
