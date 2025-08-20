<?php

declare(strict_types=1);

namespace Nutgram\Spiral\Config;

use SergiX44\Nutgram\Configuration;

final readonly class BotOptions
{
    public function __construct(
        public string $apiUrl = Configuration::DEFAULT_API_URL,
        public ?int $botId = null,
        public ?string $botName = null,
    ) {}
}
