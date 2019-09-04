<?php

/*
 * This file is part of Laravel Likeable.
 *
 * (c) Anton Komarev <anton@komarev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cog\Likeable\Tests\Stubs\Models\EntityWithMorphMap;
use Faker\Generator;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(EntityWithMorphMap::class, function (Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});
