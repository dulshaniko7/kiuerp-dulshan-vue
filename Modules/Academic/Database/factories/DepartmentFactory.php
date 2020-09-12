<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Academic\Entities\Faculty;

$factory->define(\Modules\Academic\Entities\Department::class, function (Faker $faker) {
    $suffix = 'department';
    return [
        'dept_code' => $faker->postcode,
        'dept_name' => $faker->word . ' ' . $suffix,
        'color_code' => $faker->colorName,
        'dept_status' => 0,
        'faculty_id' => function () {
            return Faculty::all()->random();
        }
    ];
});
