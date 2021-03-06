<?php

namespace Datashaman\Teams;

use Datashaman\Teams\Models\Project;
use Datashaman\Teams\Models\Team;
use Datashaman\Teams\Contracts\ProjectInterface;
use Datashaman\Teams\Contracts\TeamInterface;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

class TeamsServiceProvider extends AuthServiceProvider
{
    /**
     * @var array
     */
    protected $policies = [
        Models\Project::class => Policies\ProjectPolicy::class,
        Models\Team::class => Policies\TeamPolicy::class,
        UserInterface::class => Policies\UserPolicy::class,
    ];

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

        $this->registerPolicies();
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/teams.php', 'teams'
        );

        $this->app->bind(TeamInterface::class, Team::class);
        $this->app->bind(ProjectInterface::class, Project::class);
    }
}
