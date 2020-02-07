<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\OrderDetail;
use Faker\Generator as Faker;

$factory->define(OrderDetail::class, function (Faker $faker) {
    return [
        'order_id' => function () {
            return factory(App\Order::class)->create()->id;
        },
        'inventory_id' => function () {
            return factory(App\Inventory::class)->create()->id;
        },
        'quantity' => $faker->numberBetween($min = 100, $max = 900),
    ];
});
