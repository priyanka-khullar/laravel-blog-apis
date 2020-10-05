<?php

use App\UserDetail;
use Faker\Generator as Faker;

$factory->define(App\UserDetail::class, function (Faker $faker) {
	static $password;
    return [
        'user_id' => $faker->randomElement(['1', '2', '3', '4', '5']),
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
    ];
});