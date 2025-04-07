<?php

namespace Manu\WrapAndActionPackage\Console\Commands;

use Illuminate\Console\GeneratorCommand;

final class WrapperCreateCommand extends GeneratorCommand
{
    /** @var string */
    protected $signature = 'make:wrapper {name : The name wrapper}';

    /** @var string */
    protected $description = 'Create a new wrapper class';

    /** @var string */
    protected $type = 'Wrapper';

    public function handle(): void
    {
        parent::handle();
    }

    protected function getStub(): string
    {
        return __DIR__.'/../../stubs/wrapper.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @phpcs:disable
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Wrapper';
    }
}
