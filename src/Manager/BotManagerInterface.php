<?php

declare(strict_types=1);

namespace Nutgram\Spiral\Manager;

use SergiX44\Nutgram\Nutgram;

interface BotManagerInterface
{
    public function getDefaultBotName(): string;

    public function getBot(?string $botName = null): Nutgram;
}
