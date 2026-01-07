<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Shirts', 'description' => 'T-shirts, polo shirts, dress shirts and more'],
            ['name' => 'Caps', 'description' => 'Baseball caps, snapbacks, beanies and more'],
            ['name' => 'Shorts', 'description' => 'Athletic shorts, casual shorts, board shorts and more'],
            ['name' => 'Shoes', 'description' => 'Sneakers, boots, sandals and more'],
            ['name' => 'Pants', 'description' => 'Jeans, chinos, joggers and more'],
            ['name' => 'Jackets', 'description' => 'Hoodies, jackets, coats and more'],
            ['name' => 'Accessories', 'description' => 'Belts, wallets, bags and more'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
