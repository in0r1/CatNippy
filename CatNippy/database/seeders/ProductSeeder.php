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
        //image path to the image in storage/app/public/'folder name'/'image name'.'ext'
        $products = [
            [
                'category' => 'Catnip',
                'name' => 'Cat Trip',
                'description' => 'Dried Catnip 20 g. Natural, aromatic catnip for toys and scratching posts. Encourages cats to play actively.',
                'price' => 14.90,
                'stock' => 100,
                'image' => '/products/19_koci_odlot.png',
            ],
            [
                'category' => 'Catnip',
                'name' => 'MeowPower',
                'description' => 'Ground Catnip 30 g. Finely ground catnip, ideal for filling toys, sachets, and scent mats.',
                'price' => 19.90,
                'stock' => 80,
                'image' => '/products/20_mielona_mietka.png',
            ],
            [
                'category' => 'Catnip',
                'name' => 'Mighty Whisker',
                'description' => 'Premium Catnip 15 g. Carefully selected catnip leaves and flower heads with an intense aroma.',
                'price' => 24.90,
                'stock' => 50,
                'image' => '/products/21_mietka_mocny_was.png',
            ],
            [
                'category' => 'Catnip',
                'name' => 'Green Tomcat',
                'description' => 'Organic Catnip 25 g. Dried catnip from certified organic farms. Natural pleasure for your cat.',
                'price' => 22.90,
                'stock' => 60,
                'image' => '/products/22_zk_mietka_bio.png',
            ],
            [
                'category' => 'Catnip',
                'name' => 'Purring Garden',
                'description' => 'Catnip in a Pot. Live catnip for growing at home. Fresh leaves and natural scent within paw\'s reach.',
                'price' => 18.90,
                'stock' => 35,
                'image' => '/products/24_mietka_w_doniczce.png',
            ],
            [
                'category' => 'Catnip',
                'name' => 'Cat Refill',
                'description' => 'Dried Catnip 50 g. Economy pack of dried catnip for refilling toys and sachets.',
                'price' => 29.90,
                'stock' => 70,
                'image' => '/products/23_koci_refil.png',
            ],
            [
                'category' => 'Sprays and Aromas',
                'name' => 'Cat Magnet',
                'description' => 'Catnip Spray 100 ml. Convenient spray for toys and scratching posts. Helps spark your cat\'s interest in new accessories.',
                'price' => 24.90,
                'stock' => 65,
                'image' => '/products/25_spray_z_kocimienta.png',
            ],
            [
                'category' => 'Sprays and Aromas',
                'name' => 'Scratcher Magnet',
                'description' => 'Spray 50 ml. Aromatic catnip spray to encourage your cat to use the scratching post.',
                'price' => 17.90,
                'stock' => 55,
                'image' => '/products/26_drapakowy_magnes.png',
            ],
            [
                'category' => 'Cat Toys',
                'name' => 'Fish on a High',
                'description' => 'Soft plush fish with aromatic catnip. Perfect for catching, tossing, and kicking.',
                'price' => 29.90,
                'stock' => 45,
                'image' => '/products/27_rybka_na_haju.png',
            ],
            [
                'category' => 'Cat Toys',
                'name' => 'Januszka the Mouse',
                'description' => 'Lightweight plush mouse with catnip that encourages hunting and active play.',
                'price' => 19.90,
                'stock' => 90,
                'image' => '/products/28_myszka_januszka.png',
            ],
            [
                'category' => 'Cat Toys',
                'name' => 'Kitty Cloud',
                'description' => 'Soft cushion filled with catnip. Great for cuddling and cat play.',
                'price' => 34.90,
                'stock' => 40,
                'image' => '/products/11_kocia_chmurka.png',
            ],
            [
                'category' => 'Plant Alternatives',
                'name' => 'Stick of Happiness',
                'description' => 'Natural matatabi sticks that encourage cats to chew, sniff, and play.',
                'price' => 16.90,
                'stock' => 75,
                'image' => '/products/13_patyk_szczescia_matatabi.png',
            ],
            [
                'category' => 'Plant Alternatives',
                'name' => 'Cat Zen',
                'description' => 'Valerian Root 20 g. Aromatic valerian root for scent toys. An alternative to catnip.',
                'price' => 21.90,
                'stock' => 45,
                'image' => '/products/14_koci_zen_kozlek.png',
            ],
            [
                'category' => 'Sets and Gifts',
                'name' => 'Cat Starter Pack',
                'description' => 'Starter set: dried catnip, plush mouse, and a matatabi sample.',
                'price' => 39.90,
                'stock' => 30,
                'image' => '/products/15_koci_starter_pack.png',
            ],
            [
                'category' => 'Sets and Gifts',
                'name' => 'Premium Package',
                'description' => 'Rich set: premium catnip, spray, cushion, and matatabi sticks.',
                'price' => 89.90,
                'stock' => 20,
                'image' => '/products/16_januszowy_pakiet_premium.png',
            ],
            [
                'category' => 'Sets and Gifts',
                'name' => 'Purring Gift',
                'description' => 'Gift set with two toys and dried catnip for a special cat.',
                'price' => 59.90,
                'stock' => 25,
                'image' => '/products/17_mruczacy_prezent.png',
            ],
            [
                'category' => 'Sets and Gifts',
                'name' => 'Cat Adventure – Herbal Trio',
                'description' => 'A set of three aromatic plants: catnip, matatabi, and valerian.',
                'price' => 44.90,
                'stock' => 40,
                'image' => '/products/18_kocia_przygoda_trio_roslinne.png',
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
                    'image' => $data['image'],
                    'is_active' => true,
                ]
            );
        }
    }
}
