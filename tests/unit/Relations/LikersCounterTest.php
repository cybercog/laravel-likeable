<?php

/*
 * This file is part of Laravel Likeable.
 *
 * (c) CyberCog <support@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Likeable\Tests\Unit\Relations;

use Cog\Likeable\Tests\TestCase;
use Cog\Likeable\Models\LikersCounter;
use Cog\Likeable\Tests\Stubs\Models\Entity;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class LikersCounterTest.
 *
 * @package Cog\Likeable\Tests\Unit\Relations
 */
class LikersCounterTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_belong_to_likeable_model()
    {
        $entity = factory(Entity::class)->create();

        $entity->like(1);

        $this->assertInstanceOf(Entity::class, LikersCounter::first()->likeable);
    }
}
