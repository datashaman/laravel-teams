<?php

namespace Datashaman\Teams\Tests;

use Datashaman\Teams\TeamsServiceProvider;
use Illuminate\Foundation\Application;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $options = ['--database' => 'testing'];
        $this->loadLaravelMigrations($options);
        $this->artisan('migrate', $options)->run();
        $this->withFactories(__DIR__.'/../database/factories');
    }

    /**
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app['config']->set('teams.user', Fixtures\User::class);
    }

    /**
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            TeamsServiceProvider::class,
        ];
    }
}
