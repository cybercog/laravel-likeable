<?php

/*
 * This file is part of Laravel Likeable.
 *
 * (c) Anton Komarev <a.komarev@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Laravel\Likeable\Tests\Unit\Relations;

use Cog\Laravel\Likeable\Models\Like;
use Cog\Laravel\Likeable\Tests\Stubs\Models\Entity;
use Cog\Laravel\Likeable\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class LikeTest.
 *
 * @package Cog\Laravel\Likeable\Tests\Unit\Relations
 */
class LikeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_belong_to_likeable_model()
    {
        $entity = factory(Entity::class)->create();

        $entity->like(1);

        $this->assertInstanceOf(Entity::class, Like::first()->likeable);
    }
}
