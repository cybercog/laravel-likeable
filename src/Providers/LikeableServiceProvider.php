<?php

/*
 * This file is part of Laravel Likeable.
 *
 * (c) CyberCog <support@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Likeable\Providers;

use Cog\Likeable\Models\Like;
use Cog\Likeable\Models\LikersCounter;
use Illuminate\Support\ServiceProvider;
use Cog\Likeable\Observers\LikeObserver;
use Cog\Likeable\Services\LikeableService;
use Cog\Likeable\Console\LikeableRecountCommand;
use Cog\Likeable\Contracts\Like as LikeContract;
use Cog\Likeable\Contracts\LikersCounter as LikersCounterContract;
use Cog\Likeable\Contracts\LikeableService as LikeableServiceContract;

/**
 * Class LikeableServiceProvider.
 *
 * @package Cog\Likeable\Providers
 */
class LikeableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                LikeableRecountCommand::class,
            ]);

            $this->publishes([
                realpath(__DIR__ . '/../../database/migrations') => database_path('migrations'),
            ], 'migrations');
        }

        $this->bootObservers();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LikeContract::class, Like::class);
        $this->app->bind(LikersCounterContract::class, LikersCounter::class);
        $this->app->singleton(LikeableServiceContract::class, LikeableService::class);
    }

    /**
     * Package models observers.
     */
    protected function bootObservers()
    {
        Like::observe(new LikeObserver());
    }
}
