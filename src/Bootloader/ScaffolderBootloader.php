<?php

declare(strict_types=1);

namespace Nutgram\Spiral\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Console\Bootloader\ConsoleBootloader;
use Spiral\Scaffolder\Bootloader\ScaffolderBootloader as BaseScaffolderBootloaderAlias;

final class ScaffolderBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        ConsoleBootloader::class,
        BaseScaffolderBootloaderAlias::class,
    ];
}
