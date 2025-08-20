<?php

declare(strict_types=1);

namespace Nutgram\Spiral\Manager;

use Nutgram\Spiral\Config\Bot;
use Nutgram\Spiral\Config\NutgramConfig;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use SergiX44\Nutgram\Configuration;
use SergiX44\Nutgram\Nutgram;
use Spiral\Cache\CacheStorageProviderInterface;
use Spiral\Logger\LogsInterface;

final readonly class BotFactory
{
    public function __construct(
        private CacheStorageProviderInterface $cacheStorageProvider,
        private ContainerInterface $container,
        private LogsInterface $logs,
        private NutgramConfig $config,
    ) {}

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function create(string $botName): Nutgram
    {
        $botConfig = $this->config->botConfig($botName);
        $configuration = $this->createConfiguration($botConfig);

        return new Nutgram($botConfig->token, $configuration);
    }

    private function createConfiguration(Bot $bot): Configuration
    {
        return new Configuration(
            apiUrl: $bot->options->apiUrl,
            botId: $bot->options->botId,
            botName: $bot->options->botName,
            container: $this->container,
            cache: $this->cacheStorageProvider->storage($bot->cacheStorage),
            logger: $this->logs->getLogger($bot->logChannel),
        );
    }
}
