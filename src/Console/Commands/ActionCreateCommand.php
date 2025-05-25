<?php

namespace Manu\WrapAndActionPackage\Console\Commands;

use Illuminate\Console\GeneratorCommand;

final class ActionCreateCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:action {name : The action name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Action Class';

    protected function getStub(): string
    {
        return __DIR__.'/../../stubs/action.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\\Actions';
    }
}
