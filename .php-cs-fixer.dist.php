<?php

declare(strict_types=1);

use Spiral\CodeStyle\Builder;

require_once 'vendor/autoload.php';

return Builder::create()
    ->include(__DIR__ . '/src')
    ->include(__FILE__)
    ->build();
