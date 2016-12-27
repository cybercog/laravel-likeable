<?php

/*
 * This file is part of Laravel Likeable.
 *
 * (c) CyberCog <support@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Likeable\Tests\Stubs\Models;

use Cog\Likeable\Traits\HasLikes;
use Illuminate\Database\Eloquent\Model;
use Cog\Likeable\Contracts\HasLikes as HasLikesContract;

/**
 * Class Entity.
 *
 * @package Cog\Likeable\Tests\Stubs\Models
 */
class Entity extends Model implements HasLikesContract
{
    use HasLikes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'entity';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}
