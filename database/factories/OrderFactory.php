<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'customer_id' => function () {
            return factory(App\Customer::class)->create()->id;
        },
        'order_date' => $faker->dateTime,
        'order_notes' => $faker->sentence,
    ];
});
