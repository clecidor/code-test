<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->text(30),
        'description' => $faker->text(200),
        'price' => $faker->randomFloat(2, 1, 100),
    ];
});
