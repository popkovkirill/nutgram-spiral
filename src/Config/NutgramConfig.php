<?php

declare(strict_types=1);

namespace Nutgram\Spiral\Config;

use Spiral\Core\InjectableConfig;

final class NutgramConfig extends InjectableConfig
{
    public const CONFIG = 'nutgram';

    protected array $config = [
        'default_bot' => '',
        'webhook_host' => '',
        'bots' => [],
    ];

    public function defaultBot(): string
    {
        return $this->config['default_bot'] ?? 'default';
    }

    public function webhookHost(): string
    {
        return $this->config['webhook_host'] ?? '';
    }

    public function botConfig(string $botName): Bot
    {
        return $this->config['bots'][$botName]
            ?? throw new \LogicException("Bot '{$botName}' not found");
    }
}
