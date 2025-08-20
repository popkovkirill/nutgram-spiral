<?php

declare(strict_types=1);

namespace Nutgram\Spiral\Config;

final readonly class Bot
{
    public function __construct(
        public string $token,
        public string $logChannel = 'default',
        public string $cacheStorage = 'local',
        public BotOptions $options = new BotOptions(),
    ) {}
}
