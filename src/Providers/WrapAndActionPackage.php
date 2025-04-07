<?php

namespace Manu\WrapAndActionPackage\Providers;

use Illuminate\Support\ServiceProvider;
use Manu\WrapAndActionPackage\Console\Commands\ActionCreateCommand;
use Manu\WrapAndActionPackage\Console\Commands\WrapperCreateCommand;

class WrapAndActionPackage extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(
                commands: [
                    ActionCreateCommand::class,
                    WrapperCreateCommand::class
                ],
            );
        }
    }
}
