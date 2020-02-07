<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Inventory;
use Faker\Generator as Faker;

$factory->define(Inventory::class, function (Faker $faker) {
    return [
        'item' => $faker->word,
        'description' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'quantity' => $faker->numberBetween($min = 100, $max = 900),
        'price' => $faker->numberBetween($min = 100, $max = 900),
    ];
});
