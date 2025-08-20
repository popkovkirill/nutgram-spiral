<?php

declare(strict_types=1);

namespace Nutgram\Spiral\Manager;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SergiX44\Nutgram\Nutgram;

final class BotManager implements BotManagerInterface
{
    /**
     * @var Nutgram[]
     */
    private array $bots = [];

    public function __construct(
        public BotFactory $botFactory,
        public string $defaultBot,
    ) {}

    #[\Override]
    public function getDefaultBotName(): string
    {
        return $this->defaultBot;
    }

    /**
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[\Override]
    public function getBot(?string $botName = null): Nutgram
    {
        if (!isset($this->bots[$botName])) {
            $this->bots[$botName] = $this->createBot($botName ?? $this->defaultBot);
        }

        return $this->bots[$botName];
    }

    /**
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function createBot(string $botName): Nutgram
    {
        return $this->botFactory
            ->create($botName);
    }
}
