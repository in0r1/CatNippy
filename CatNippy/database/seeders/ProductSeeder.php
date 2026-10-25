<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'category' => 'Catnip',
                'name' => 'Cat Trip',
                'description' => 'Dried Catnip 20 g. Natural, aromatic catnip for toys and scratching posts. Encourages cats to play actively.',
                'price' => 14.90,
                'stock' => 100,
            ],
            [
                'category' => 'Catnip',
                'name' => 'MeowPower',
                'description' => 'Ground Catnip 30 g. Finely ground catnip, ideal for filling toys, sachets, and scent mats.',
                'price' => 19.90,
                'stock' => 80,
            ],
            [
                'category' => 'Catnip',
                'name' => 'Mighty Whisker',
                'description' => 'Premium Catnip 15 g. Carefully selected catnip leaves and flower heads with an intense aroma.',
                'price' => 24.90,
                'stock' => 50,
            ],
            [
                'category' => 'Catnip',
                'name' => 'Green Tomcat',
                'description' => 'Organic Catnip 25 g. Dried catnip from certified organic farms. Natural pleasure for your cat.',
                'price' => 22.90,
                'stock' => 60,
            ],
            [
                'category' => 'Catnip',
                'name' => 'Purring Garden',
                'description' => 'Catnip in a Pot. Live catnip for growing at home. Fresh leaves and natural scent within paw\'s reach.',
                'price' => 18.90,
                'stock' => 35,
            ],
            [
                'category' => 'Catnip',
                'name' => 'Cat Refill',
                'description' => 'Dried Catnip 50 g. Economy pack of dried catnip for refilling toys and sachets.',
                'price' => 29.90,
                'stock' => 70,
            ],
            [
                'category' => 'Sprays and Aromas',
                'name' => 'Cat Magnet',
                'description' => 'Catnip Spray 100 ml. Convenient spray for toys and scratching posts. Helps spark your cat\'s interest in new accessories.',
                'price' => 24.90,
                'stock' => 65,
            ],
            [
                'category' => 'Sprays and Aromas',
                'name' => 'Scratcher Magnet',
                'description' => 'Spray 50 ml. Aromatic catnip spray to encourage your cat to use the scratching post.',
                'price' => 17.90,
                'stock' => 55,
            ],
            [
                'category' => 'Cat Toys',
                'name' => 'Fish on a High',
                'description' => 'Soft plush fish with aromatic catnip. Perfect for catching, tossing, and kicking.',
                'price' => 29.90,
                'stock' => 45,
            ],
            [
                'category' => 'Cat Toys',
                'name' => 'Januszka the Mouse',
                'description' => 'Lightweight plush mouse with catnip that encourages hunting and active play.',
                'price' => 19.90,
                'stock' => 90,
            ],
            [
                'category' => 'Cat Toys',
                'name' => 'Kitty Cloud',
                'description' => 'Soft cushion filled with catnip. Great for cuddling and cat play.',
                'price' => 34.90,
                'stock' => 40,
            ],
            [
                'category' => 'Plant Alternatives',
                'name' => 'Stick of Happiness',
                'description' => 'Natural matatabi sticks that encourage cats to chew, sniff, and play.',
                'price' => 16.90,
                'stock' => 75,
            ],
            [
                'category' => 'Plant Alternatives',
                'name' => 'Cat Zen',
                'description' => 'Valerian Root 20 g. Aromatic valerian root for scent toys. An alternative to catnip.',
                'price' => 21.90,
                'stock' => 45,
            ],
            [
                'category' => 'Sets and Gifts',
                'name' => 'Cat Starter Pack',
                'description' => 'Starter set: dried catnip, plush mouse, and a matatabi sample.',
                'price' => 39.90,
                'stock' => 30,
            ],
            [
                'category' => 'Sets and Gifts',
                'name' => 'Premium Package',
                'description' => 'Rich set: premium catnip, spray, cushion, and matatabi sticks.',
                'price' => 89.90,
                'stock' => 20,
            ],
            [
                'category' => 'Sets and Gifts',
                'name' => 'Purring Gift',
                'description' => 'Gift set with two toys and dried catnip for a special cat.',
                'price' => 59.90,
                'stock' => 25,
            ],
            [
                'category' => 'Sets and Gifts',
                'name' => 'Cat Adventure – Herbal Trio',
                'description' => 'A set of three aromatic plants: catnip, matatabi, and valerian.',
                'price' => 44.90,
                'stock' => 40,
            ],
        ];
        foreach ($products as $data) {
            $category = Category::where(
                'name',
                $data['category']
            )->firstOrFail();

            Product::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'category_id' => $category->id,
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'price' => $data['price'],
                    'stock' => $data['stock'],
                    'is_active' => true,
                ]
            );
        }
    }
}
