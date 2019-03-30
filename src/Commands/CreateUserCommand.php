<?php

namespace Datashaman\Teams\Commands;

use Illuminate\Console\Command;

class CreateUserCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'teams:create-user {name} {email} {password} {--roles=}';

    /**
     * @var string
     */
    protected $description = 'Create a teams user';

    public function handle()
    {
        $attrs = $this->argument();
        $class = config('teams.user');
        $user = $class::create($attrs);

        $roles = collect(explode(',', $this->option('roles')))
            ->each(
                function ($role) use ($user) {
                    $user->addRole($role);
                }
            );

        if ($user->exists) {
            $this->info('User ' . $user->email . ' created');
            $this->info('API Token: ' . $user->api_token);
        } else {
            $this->error('User ' . $user->email . ' NOT created');
            exit(1);
        }
    }
}
