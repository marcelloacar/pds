<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Shop\Categories\Category;
use Illuminate\Http\UploadedFile;

$factory->define(Category::class, function (Faker\Generator $faker) {
    $name = $faker->unique()->randomElement([
        'Rodas',
        'Acessórios',
        'Óleo',
        'Vidros'
    ]);

    $file = UploadedFile::fake()->image('category.png', 600, 600);

    return [
        'name' => $name,
        'slug' => str_slug($name),
        'description' => $faker->paragraph,
        'cover' => $file->store(env('AWS_ROOT_FOLDER') . '/public/categories'),
        'status' => 1
    ];
});
