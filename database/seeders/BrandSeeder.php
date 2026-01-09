<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Uniqlo',
                'slug' => 'uniqlo',
                'description' => 'Japanese casual wear designer and retailer',
                'is_active' => true,
            ],
            [
                'name' => 'H&M',
                'slug' => 'h-and-m',
                'description' => 'Swedish multinational clothing company',
                'is_active' => true,
            ],
            [
                'name' => 'Penshoppe',
                'slug' => 'penshoppe',
                'description' => 'Filipino clothing brand',
                'is_active' => true,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['slug' => $brand['slug']],
                $brand
            );
        }
    }
}
