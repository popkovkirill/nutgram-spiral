<?php

declare(strict_types=1);

namespace Nutgram\Spiral\Http;

use Cocur\Slugify\SlugifyInterface;
use Nutgram\Spiral\Config\NutgramConfig;
use Nutgram\Spiral\Manager\BotManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Spiral\Http\Exception\ClientException\ForbiddenException;

final readonly class WebhookController
{
    public function __construct(
        private NutgramConfig $config,
        private BotManagerInterface $botManager,
        private SlugifyInterface $slugify,
    ) {}

    /**
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(HttpWebhookMode $webhook, string $name, string $token): void
    {
        $bot = $this->botManager->getBot($name);
        $config = $this->config->botConfig($name);

        if ($token != $this->slugify->slugify($config->token)) {
            throw new ForbiddenException();
        }

        $bot->setRunningMode($webhook);
        $bot->run();
    }
}
