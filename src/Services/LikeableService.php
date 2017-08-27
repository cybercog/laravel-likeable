<?php

/*
 * This file is part of Laravel Likeable.
 *
 * (c) Anton Komarev <a.komarev@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Laravel\Likeable\Services;

use Cog\Contracts\Likeable\Likeable as LikeableContract;
use Cog\Contracts\Likeable\Like\Like as LikeContract;
use Cog\Contracts\Likeable\LikeableService as LikeableServiceContract;
use Cog\Contracts\Likeable\LikeCounter\LikeCounter as LikeCounterContract;
use Cog\Laravel\Likeable\Like\Enums\LikeType;
use Cog\Laravel\Likeable\Exceptions\LikerNotDefinedException;
use Cog\Laravel\Likeable\Exceptions\LikeTypeInvalidException;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class LikeableService.
 *
 * @package Cog\Laravel\Likeable\Services
 */
class LikeableService implements LikeableServiceContract
{
    /**
     * Add a like to likeable model by user.
     *
     * @param \Cog\Contracts\Likeable\Likeable $likeable
     * @param string $type
     * @param string $userId
     * @return void
     *
     * @throws \Cog\Laravel\Likeable\Exceptions\LikerNotDefinedException
     */
    public function addLikeTo(LikeableContract $likeable, $type, $userId)
    {
        $userId = $this->getLikerUserId($userId);

        $like = $likeable->likesAndDislikes()->where([
            'user_id' => $userId,
        ])->first();

        if (!$like) {
            $likeable->likes()->create([
                'user_id' => $userId,
                'type_id' => $this->getLikeTypeId($type),
            ]);

            return;
        }

        if ($like->type_id == $this->getLikeTypeId($type)) {
            return;
        }

        $like->delete();

        $likeable->likes()->create([
            'user_id' => $userId,
            'type_id' => $this->getLikeTypeId($type),
        ]);
    }

    /**
     * Remove a like to likeable model by user.
     *
     * @param \Cog\Contracts\Likeable\Likeable $likeable
     * @param string $type
     * @param int|null $userId
     * @return void
     *
     * @throws \Cog\Laravel\Likeable\Exceptions\LikerNotDefinedException
     */
    public function removeLikeFrom(LikeableContract $likeable, $type, $userId)
    {
        $like = $likeable->likesAndDislikes()->where([
            'user_id' => $this->getLikerUserId($userId),
            'type_id' => $this->getLikeTypeId($type),
        ])->first();

        if (!$like) {
            return;
        }

        $like->delete();
    }

    /**
     * Toggle like for model by the given user.
     *
     * @param \Cog\Contracts\Likeable\Likeable $likeable
     * @param string $type
     * @param string $userId
     * @return void
     *
     * @throws \Cog\Laravel\Likeable\Exceptions\LikerNotDefinedException
     */
    public function toggleLikeOf(LikeableContract $likeable, $type, $userId)
    {
        $userId = $this->getLikerUserId($userId);

        $like = $likeable->likesAndDislikes()->where([
            'user_id' => $userId,
            'type_id' => $this->getLikeTypeId($type),
        ])->exists();

        if ($like) {
            $this->removeLikeFrom($likeable, $type, $userId);
        } else {
            $this->addLikeTo($likeable, $type, $userId);
        }
    }

    /**
     * Has the user already liked likeable model.
     *
     * @param \Cog\Contracts\Likeable\Likeable $likeable
     * @param string $type
     * @param int|null $userId
     * @return bool
     */
    public function isLiked(LikeableContract $likeable, $type, $userId)
    {
        if (is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        if (!$userId) {
            return false;
        }

        return $likeable->likesAndDislikes()->where([
            'user_id' => $userId,
            'type_id' => $this->getLikeTypeId($type),
        ])->exists();
    }

    /**
     * Decrement the total like count stored in the counter.
     *
     * @param \Cog\Contracts\Likeable\Likeable $likeable
     * @return void
     */
    public function decrementLikesCount(LikeableContract $likeable)
    {
        $counter = $likeable->likesCounter()->first();

        if (!$counter) {
            return;
        }

        $counter->decrement('count');
    }

    /**
     * Increment the total like count stored in the counter.
     *
     * @param \Cog\Contracts\Likeable\Likeable $likeable
     * @return void
     */
    public function incrementLikesCount(LikeableContract $likeable)
    {
        $counter = $likeable->likesCounter()->first();

        if (!$counter) {
            $counter = $likeable->likesCounter()->create([
                'count' => 0,
                'type_id' => LikeType::LIKE,
            ]);
        }

        $counter->increment('count');
    }

    /**
     * Decrement the total dislike count stored in the counter.
     *
     * @param \Cog\Contracts\Likeable\Likeable $likeable
     * @return void
     */
    public function decrementDislikesCount(LikeableContract $likeable)
    {
        $counter = $likeable->dislikesCounter()->first();

        if (!$counter) {
            return;
        }

        $counter->decrement('count');
    }

    /**
     * Increment the total dislike count stored in the counter.
     *
     * @param \Cog\Contracts\Likeable\Likeable $likeable
     * @return void
     */
    public function incrementDislikesCount(LikeableContract $likeable)
    {
        $counter = $likeable->dislikesCounter()->first();

        if (!$counter) {
            $counter = $likeable->dislikesCounter()->create([
                'count' => 0,
                'type_id' => LikeType::DISLIKE,
            ]);
        }

        $counter->increment('count');
    }

    /**
     * Remove like counters by likeable type.
     *
     * @param string $likeableType
     * @param string|null $type
     * @return void
     */
    public function removeLikeCountersOfType($likeableType, $type = null)
    {
        if (class_exists($likeableType)) {
            /** @var \Cog\Contracts\Likeable\Likeable $likeable */
            $likeable = new $likeableType;
            $likeableType = $likeable->getMorphClass();
        }

        /** @var \Illuminate\Database\Eloquent\Builder $counters */
        $counters = app(LikeCounterContract::class)->where('likeable_type', $likeableType);
        if (!is_null($type)) {
            $counters->where('type_id', $this->getLikeTypeId($type));
        }
        $counters->delete();
    }

    /**
     * Remove all likes from likeable model.
     *
     * @param \Cog\Contracts\Likeable\Likeable $likeable
     * @param string $type
     * @return void
     */
    public function removeModelLikes(LikeableContract $likeable, $type)
    {
        app(LikeContract::class)->where([
            'likeable_id' => $likeable->getKey(),
            'likeable_type' => $likeable->getMorphClass(),
            'type_id' => $this->getLikeTypeId($type),
        ])->delete();

        app(LikeCounterContract::class)->where([
            'likeable_id' => $likeable->getKey(),
            'likeable_type' => $likeable->getMorphClass(),
            'type_id' => $this->getLikeTypeId($type),
        ])->delete();
    }

    /**
     * Get collection of users who liked entity.
     *
     * @param \Cog\Contracts\Likeable\Likeable $likeable
     * @return \Illuminate\Support\Collection
     */
    public function collectLikersOf(LikeableContract $likeable)
    {
        $userModel = $this->resolveUserModel();

        $likersIds = $likeable->likes->pluck('user_id');

        return $userModel::whereKey($likersIds)->get();
    }

    /**
     * Get collection of users who disliked entity.
     *
     * @param \Cog\Contracts\Likeable\Likeable $likeable
     * @return \Illuminate\Support\Collection
     */
    public function collectDislikersOf(LikeableContract $likeable)
    {
        $userModel = $this->resolveUserModel();

        $likersIds = $likeable->dislikes->pluck('user_id');

        return $userModel::whereKey($likersIds)->get();
    }

    /**
     * Fetch records that are liked by a given user id.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @param int|null $userId
     * @return \Illuminate\Database\Eloquent\Builder
     *
     * @throws \Cog\Laravel\Likeable\Exceptions\LikerNotDefinedException
     */
    public function scopeWhereLikedBy(Builder $query, $type, $userId)
    {
        $userId = $this->getLikerUserId($userId);

        return $query->whereHas('likesAndDislikes', function (Builder $innerQuery) use ($type, $userId) {
            $innerQuery->where('user_id', $userId);
            $innerQuery->where('type_id', $this->getLikeTypeId($type));
        });
    }

    /**
     * Fetch records sorted by likes count.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $likeType
     * @param string $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByLikesCount(Builder $query, $likeType, $direction = 'desc')
    {
        $likeable = $query->getModel();

        return $query
            ->select($likeable->getTable() . '.*', 'like_counters.count')
            ->leftJoin('like_counters', function (JoinClause $join) use ($likeable, $likeType) {
                $join
                    ->on('like_counters.likeable_id', '=', "{$likeable->getTable()}.{$likeable->getKeyName()}")
                    ->where('like_counters.likeable_type', '=', $likeable->getMorphClass())
                    ->where('like_counters.type_id', '=', $this->getLikeTypeId($likeType));
            })
            ->orderBy('like_counters.count', $direction);
    }

    /**
     * Fetch likes counters data.
     *
     * @param string $likeableType
     * @param string $likeType
     * @return array
     */
    public function fetchLikesCounters($likeableType, $likeType)
    {
        /** @var \Illuminate\Database\Eloquent\Builder $likesCount */
        $likesCount = app(LikeContract::class)
            ->select([
                DB::raw('COUNT(*) AS count'),
                'likeable_type',
                'likeable_id',
                'type_id',
            ])
            ->where('likeable_type', $likeableType);

        if (!is_null($likeType)) {
            $likesCount->where('type_id', $this->getLikeTypeId($likeType));
        }

        $likesCount->groupBy('likeable_id', 'type_id');

        return $likesCount->get()->toArray();
    }

    /**
     * Get current user id or get user id passed in.
     *
     * @param int $userId
     * @return int
     *
     * @throws \Cog\Laravel\Likeable\Exceptions\LikerNotDefinedException
     */
    protected function getLikerUserId($userId)
    {
        if (is_null($userId)) {
            $userId = $this->loggedInUserId();
        }

        if (!$userId) {
            throw new LikerNotDefinedException();
        }

        return $userId;
    }

    /**
     * Fetch the primary ID of the currently logged in user.
     *
     * @return int
     */
    protected function loggedInUserId()
    {
        return auth()->id();
    }

    /**
     * Get like type id from name.
     *
     * @todo move to Enum class
     * @param string $type
     * @return int
     *
     * @throws \Cog\Laravel\Likeable\Exceptions\LikeTypeInvalidException
     */
    protected function getLikeTypeId($type)
    {
        $type = strtoupper($type);
        if (!defined("\\Cog\\Laravel\\Likeable\\Like\\Enums\\LikeType::{$type}")) {
            throw new LikeTypeInvalidException("Like type `{$type}` not exist");
        }

        return constant("\\Cog\\Laravel\\Likeable\\Like\\Enums\\LikeType::{$type}");
    }

    /**
     * Retrieve User's model class name.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    private function resolveUserModel()
    {
        return config('auth.providers.users.model');
    }
}
