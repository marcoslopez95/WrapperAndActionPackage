<?php

namespace Manu\WrapAndActionPackage\Tests;

use Orchestra\Testbench\TestCase;
use Manu\WrapAndActionPackage\Providers\WrapAndActionPackage;

class WrapAndActionPackageTestCase extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            WrapAndActionPackage::class,
        ];
    }
}
