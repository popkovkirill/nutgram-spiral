<?php

declare(strict_types=1);

namespace Nutgram\Spiral\Bootloader;

use Nutgram\Spiral\Http\WebhookController;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Router\Loader\Configurator\RoutingConfigurator;

final class WebhookBootloader extends Bootloader
{
    public function init(RoutingConfigurator $configurator): void
    {
        $configurator->add(name: 'api.nutgram.webhook', pattern: 'api/webhook/<name>/<token>')
            ->methods(['POST', 'GET'])
            ->controller(WebhookController::class);
    }
}
