<?php

use Faker\Generator as Faker;
use App\Eloquent\Book;
use App\Eloquent\User;
use App\Eloquent\Category;
use App\Eloquent\Media;
use Carbon\Carbon;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('123456'),
        'phone' => $faker->unique()->phoneNumber,
        'employee_code' => str_random(6),
        'position' => $faker->word,
        'remember_token' => str_random(10),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(3),
        'slug' => str_random(10),
        'description' => $faker->sentence(12),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});

$factory->define(Book::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(6),
        'slug' => str_random(10),
        'description' => $faker->paragraph(200),
        'author' => $faker->name,
        'avg_star' => $faker->numberBetween(0, 5),
        'sku' => str_random(10),
        'count_viewed' => $faker->numberBetween(20, 100),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});
