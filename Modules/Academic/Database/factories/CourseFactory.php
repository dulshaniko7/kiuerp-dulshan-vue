<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Academic\Entities\Department;

$factory->define(\Modules\Academic\Entities\Course::class, function (Faker $faker) {
    return [
        'course_name' => $faker->word,
        'course_category' => $faker->numberBetween(1, 10),
        'course_code' => $faker->postcode,
        'status' => 1,
        'dept_id' => function () {
            return Department::all()->random();
        }
    ];
});
