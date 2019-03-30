<?php

namespace Datashaman\Teams\Commands;

use Hash;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

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
        $class = config('teams.user');

        $user = new $class();
        $user->name = $this->argument('name');
        $user->email = $this->argument('email');
        $user->password = Hash::make($this->argument('password'));
        $user->save();

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
