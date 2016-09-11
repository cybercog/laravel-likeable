<?php

/*
 * This file is part of Laravel Likeable.
 *
 * (c) CyberCog <support@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Likeable\Models;

use Cog\Likeable\Contracts\LikersCounter as LikersCounterContract;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LikersCounter.
 *
 * @property int type_id
 * @property int count
 * @package Cog\Likeable\Models
 */
class LikersCounter extends Model implements LikersCounterContract
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'likers_counter';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_id',
        'count',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'count' => 'integer',
    ];

    /**
     * Likeable model relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function likeable()
    {
        return $this->morphTo();
    }
}
