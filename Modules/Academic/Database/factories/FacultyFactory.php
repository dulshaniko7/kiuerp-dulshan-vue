<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\Modules\Academic\Entities\Faculty::class, function (Faker $faker) {
    $faculty_status = 0;
    return [
        'faculty_code' => $faker->numberBetween(1000, 10000000),
        'faculty_name' => $faker->word,

        'color_code' => $faker->colorName,
        'faculty_status' => $faculty_status,
    ];
});
