<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo danh mục cha
        $categories = [
            [
                'name' => 'Tableware',
                'slug' => 'tableware',
                'description' => '<p>High-quality tableware for your dining needs.</p>',
                'avatar' => 'https://poliva.vn/wp-content/uploads/2019/09/tableware-la-gi-1-1.jpeg',
                'sub_categories' => [
                    [
                        'name' => 'Plates',
                        'slug' => 'plates',
                        'description' => '<p>Various types of plates for dining.</p>',
                        'avatar' => 'https://lillyandlula.com/cdn/shop/files/White-Scalloped-Ceramic-Tableware-Plates-Bowls-6-Sizes-Sets-Lilly-Lula-6094.webp?v=1708377753&width=1445',
                    ],
                    [
                        'name' => 'Bowls',
                        'slug' => 'bowls',
                        'description' => '<p>Bowls for soups, salads, and more.</p>',
                        'avatar' => 'https://www.lecreuset.com/on/demandware.static/-/Sites-LeCreuset-Library/default/dw838b4f36/images/blog/products/dinnerware/Chambray-Dinnerware-1120x700.jpg',
                    ],
                    [
                        'name' => 'Cups & Mugs',
                        'slug' => 'cups-mugs',
                        'description' => '<p>Stylish cups and mugs for beverages.</p>',
                        'avatar' => 'https://salt.tikicdn.com/cache/w300/ts/product/41/12/55/f5883004f556c4909a67c46f91b2fb5d.jpg',
                    ],
                ],
            ],
            [
                'name' => 'Dinnerware Sets',
                'slug' => 'dinnerware-sets',
                'description' => '<p>Complete dinnerware sets for your dining table.</p>',
                'avatar' => 'https://store.longphuong.vn/wp-content/uploads/2023/07/hoang-kim-19-mon.jpg',
                'sub_categories' => [
                    [
                        'name' => 'Casual Dinnerware',
                        'slug' => 'casual-dinnerware',
                        'description' => '<p>Everyday dinnerware sets for casual dining.</p>',
                        'avatar' => 'https://www.foodandwine.com/thmb/2wdi5vdE1J1CWtUiXNI-0ZZktjY=/fit-in/1500x1335/filters:no_upscale():max_bytes(150000):strip_icc()/faw-west-elm-kanto-stoneware-dinnerware-set-kristin-montemarano-4-3c83a0dcbdc54ff08aa8463f1311b1fd.jpeg',
                    ],
                    [
                        'name' => 'Formal Dinnerware',
                        'slug' => 'formal-dinnerware',
                        'description' => '<p>Elegant dinnerware sets for formal occasions.</p>',
                        'avatar' => 'https://image.made-in-china.com/202f0j00zUQcAvFGZCuS/Kiln-Transformation-Ceramic-Tableware-Retro-Ceramic-Tableware-Dinnerware-Set-Plates-for-Home.webp',
                    ],
                ],
            ],
            [
                'name' => 'Cutlery',
                'slug' => 'cutlery',
                'description' => '<p>High-quality cutlery for every meal.</p>',
                'avatar' => 'https://images-eu.ssl-images-amazon.com/images/I/71T2w4NyQyL._AC_UL210_SR210,210_.jpg',
                'sub_categories' => [
                    [
                        'name' => 'Knives',
                        'slug' => 'knives',
                        'description' => '<p>Sharp and durable knives for cutting.</p>',
                        'avatar' => 'https://ezcloud.vn/wp-content/uploads/2024/02/cutlery-la-gi.webp',
                    ],
                    [
                        'name' => 'Forks',
                        'slug' => 'forks',
                        'description' => '<p>Elegant forks for dining.</p>',
                        'avatar' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSkZODD2uB_vEjt8bFue110AvO077lzanjZ4w&s',
                    ],
                    [
                        'name' => 'Spoons',
                        'slug' => 'spoons',
                        'description' => '<p>Various types of spoons for different uses.</p>',
                        'avatar' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS9HxGpkp5uKXQC8Y820b1tFczLCVqn3HBNYg&s',
                    ],
                ],
            ],
            [
                'name' => 'Glassware',
                'slug' => 'glassware',
                'description' => '<p>Beautiful glassware for beverages.</p>',
                'avatar' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQH2lLUK46aVNlIfXga_laby47jCnshiIFMug&s',
                'sub_categories' => [
                    [
                        'name' => 'Wine Glasses',
                        'slug' => 'wine-glasses',
                        'description' => '<p>Elegant wine glasses for special occasions.</p>',
                        'avatar' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRHGj8ohmnPV_2GZ1BgughtzaZwe-ZMg_E9Vg&s',
                    ],
                    [
                        'name' => 'Tumblers',
                        'slug' => 'tumblers',
                        'description' => '<p>Durable tumblers for everyday use.</p>',
                        'avatar' => 'https://www.thespruceeats.com/thmb/t6ehbQ13XznY1F8mBvYqWsktaSA=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/spre-group-shot-tamara-staples-01.jpg-82cf2a0f4f2f4d7d95153438c3153947.jpg',
                    ],
                ],
            ],
            [
                'name' => 'Serveware',
                'slug' => 'serveware',
                'description' => '<p>Serveware for presenting your meals beautifully.</p>',
                'avatar' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTVOyDtkw0-2CzyMXTdsci-YljtiaRS51NCoA&s',
                'sub_categories' => [
                    [
                        'name' => 'Serving Trays',
                        'slug' => 'serving-trays',
                        'description' => '<p>Stylish serving trays for any occasion.</p>',
                        'avatar' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTu5GLX2lZXq08ZcyIgSiLCBPK_WFCmHr861Q&s',
                    ],
                    [
                        'name' => 'Serving Bowls',
                        'slug' => 'serving-bowls',
                        'description' => '<p>Large bowls for serving salads and more.</p>',
                        'avatar' => 'https://modernquests.com/cdn/shop/files/koziol-germany-club-serving-bowls-set-of-4-desert-sand-1.jpg?v=1690061056',
                    ],
                ],
            ],
        ];

        // Tạo danh mục cha
        foreach ($categories as $category) {
            $data = [
                'name' => $category['name'],
                'slug' => $category['slug'],
                'description' => $category['description'],
                'avatar' => $category['avatar'],
            ];
            $categoryCreate = Category::create($data);

            // Kiểm tra và tạo danh mục con nếu có
            if (isset($category['sub_categories']) && is_array($category['sub_categories'])) {
                foreach ($category['sub_categories'] as $sub_category) {
                    $sub_category_data = [
                        'name' => $sub_category['name'],
                        'slug' => $sub_category['slug'],
                        'description' => $sub_category['description'],
                        'avatar' => $sub_category['avatar'],
                        'parent_id' => $categoryCreate->id,
                    ];
                    Category::create($sub_category_data);
                }
            }
        }
    }
}
