<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Product;
use App\Models\User;
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::factory()
        ->count(5)
        ->hasImages(4)
        ->hasDiscount()
        ->create();

        Product::factory()
        ->count(10)
        ->hasImages(10)
        ->hasDiscount()
        ->create();

        User::factory()
        ->count(1)
        ->create();
    }
}
