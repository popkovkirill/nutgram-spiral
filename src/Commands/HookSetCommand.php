<?php

declare(strict_types=1);

namespace Nutgram\Spiral\Commands;

use GuzzleHttp\Exception\GuzzleException;
use Nutgram\Spiral\Config\NutgramConfig;
use Nutgram\Spiral\Manager\BotManagerInterface;
use SergiX44\Nutgram\Telegram\Exceptions\TelegramException;
use Spiral\Console\Attribute\AsCommand;
use Spiral\Console\Attribute\Option;
use Spiral\Console\Command;
use Spiral\Router\RouterInterface;

#[AsCommand(name: 'nutgram:hook:set', description: 'Remove the bot webhook')]
final class HookSetCommand extends Command
{
    #[Option(name: 'bot', shortcut: 'b')]
    private string|null $botName = null;

    /**
     *
     * @throws GuzzleException
     * @throws \JsonException
     * @throws TelegramException
     */
    public function __invoke(BotManagerInterface $botManager, NutgramConfig $config, RouterInterface $router): int
    {
        $botName = $this->botName ?? $config->defaultBot();
        $botConfig = $config->botConfig($botName);

        $webhookHost = \parse_url($config->webhookHost());

        $url = (string) $router->uri('api.nutgram.webhook', ['token' => $botConfig->token, 'name' => $botName])
            ->withScheme($webhookHost['scheme'] ?? 'http')
            ->withHost($webhookHost['host'] ?? 'localhost');

        $botManager->getBot($botName)
            ->setWebhook($url);

        $this->info("Bot [$botName] webhook set with url: $url");

        return self::SUCCESS;
    }
}
