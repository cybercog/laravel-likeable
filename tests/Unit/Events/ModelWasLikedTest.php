<?php

/*
 * This file is part of Laravel Likeable.
 *
 * (c) Anton Komarev <a.komarev@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Laravel\Likeable\Tests\Unit\Events;

use Cog\Laravel\Likeable\Events\ModelWasLiked;
use Cog\Laravel\Likeable\Tests\Stubs\Models\Entity;
use Cog\Laravel\Likeable\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class ModelWasLikedTest.
 *
 * @package Cog\Laravel\Likeable\Tests\Unit\Events
 */
class ModelWasLikedTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_fire_model_was_liked_event()
    {
        $this->expectsEvents(ModelWasLiked::class);

        $entity = factory(Entity::class)->create();
        $entity->like(1);
    }
}
