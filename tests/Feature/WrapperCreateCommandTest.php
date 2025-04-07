<?php

use Manu\WrapAndActionPackage\Console\Commands\WrapperCreateCommand;
use function PHPUnit\Framework\assertTrue;
use Illuminate\Support\Facades\File;

it('can run the command successfully', function () {
    $class = 'Test';
    $this->artisan(
        WrapperCreateCommand::class,
        ['name' => $class],
    )->assertSuccessful();

    assertTrue(
        File::exists(
            path: app_path("Wrapper/$class.php"),
        ),
    );
});
