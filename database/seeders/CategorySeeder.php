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
            ['name' => 'T-Shirts', 'description' => 'Casual and comfortable t-shirts for everyday wear'],
            ['name' => 'Polo Shirts', 'description' => 'Classic polo shirts for a smart casual look'],
            ['name' => 'Hoodies', 'description' => 'Warm and comfortable hoodies for any season'],
            ['name' => 'Jackets', 'description' => 'Stylish jackets and coats to keep you warm'],
            ['name' => 'Pants', 'description' => 'Comfortable pants for all occasions'],
            ['name' => 'Shorts', 'description' => 'Athletic and casual shorts for warm weather'],
            ['name' => 'Caps / Hats', 'description' => 'Trendy caps and hats to complete your look'],
            ['name' => 'Accessories', 'description' => 'Essential accessories including belts, bags, and more'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
