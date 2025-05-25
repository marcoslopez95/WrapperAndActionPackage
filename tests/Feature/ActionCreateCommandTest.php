<?php

use Manu\WrapAndActionPackage\Console\Commands\ActionCreateCommand;
use function PHPUnit\Framework\assertTrue;
use Illuminate\Support\Facades\File;

it('can run the command successfully', function () {
    $class = 'Test';
    $this->artisan(
        ActionCreateCommand::class,
        ['name' => $class],
    )->assertSuccessful();

    assertTrue(
        File::exists(
            path: app_path("Actions/$class.php"),
        ),
    );
});
