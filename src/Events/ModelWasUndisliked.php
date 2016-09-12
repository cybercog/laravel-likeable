<?php

/*
 * This file is part of Laravel Likeable.
 *
 * (c) CyberCog <support@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Likeable\Events;

use Cog\Likeable\Contracts\HasLikes as HasLikesContract;

/**
 * Class ModelWasUndisliked.
 *
 * @package Cog\Likeable\Events
 */
class ModelWasUndisliked
{
    /**
     * The undisliked model.
     *
     * @var \Cog\Likeable\Contracts\HasLikes
     */
    public $model;

    /**
     * User id who unliked model.
     *
     * @var int
     */
    public $likerId;

    /**
     * Create a new event instance.
     *
     * @param \Cog\Likeable\Contracts\HasLikes $model
     * @param int $likerId
     */
    public function __construct(HasLikesContract $model, $likerId)
    {
        $this->model = $model;
        $this->likerId = $likerId;
    }
}
