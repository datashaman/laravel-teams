<?php

namespace Datashaman\Teams;

use Illuminate\Support\ServiceProvider;

class TeamsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes(
            [
                __DIR__.'/../config/teams.php' => config_path('teams.php'),
            ]
        );

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes.php');

        if ($this->app->runningInConsole()) {
            $this->commands(
                [
                    Commands\CreateUserCommand::class,
                ]
            );
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/teams.php', 'teams'
        );
    }
}
