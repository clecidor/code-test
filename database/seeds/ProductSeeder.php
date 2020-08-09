<?php

use Illuminate\Database\Seeder;
use \App\Product;
use \App\User;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Add 3 products to each user
        User::all()->each(function(User $user) {
            factory(Product::class, 3)
                ->make()
                ->each(function(Product $product) use ($user) {
                    $product->user()->associate($user)->save();
                });
        });
    }
}
