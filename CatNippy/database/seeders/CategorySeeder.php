<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Catnip',
                'description' => 'Natural dried, ground, and fresh catnip.',
            ],
            [
                'name' => 'Sprays and Aromas',
                'description' => 'Plant-based scents that encourage cats to play.',
            ],
            [
                'name' => 'Cat Toys',
                'description' => 'Catnip toys for small and big hunters.',
            ],
            [
                'name' => 'Plant Alternatives',
                'description' => 'Matatabi, valerian root, and other plant attractions.',
            ],
            [
                'name' => 'Sets and Gifts',
                'description' => 'Ready-made product sets for cats.',
            ],
        ];

        foreach ($categories as $data) {
            Category::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                $data
            );
        }
    }
}
