<?php

declare(strict_types=1);

namespace Nutgram\Spiral\Commands;

use GuzzleHttp\Exception\GuzzleException;
use Nutgram\Spiral\Manager\BotManagerInterface;
use SergiX44\Nutgram\Telegram\Exceptions\TelegramException;
use Spiral\Console\Attribute\AsCommand;
use Spiral\Console\Attribute\Option;
use Spiral\Console\Command;

#[AsCommand(name: 'nutgram:hook:info', description: 'Get current webhook status')]
final class HookInfoCommand extends Command
{
    #[Option(name: 'bot', shortcut: 'b')]
    private string|null $botName = null;

    /**
     *
     * @throws GuzzleException
     * @throws \JsonException
     * @throws TelegramException
     */
    public function __invoke(BotManagerInterface $botManager): int
    {
        $botName = $this->botName ?? $botManager->getDefaultBotName();
        $bot = $botManager->getBot($botName);
        $webhookInfo = $bot->getWebhookInfo();

        if (\is_null($webhookInfo)) {
            $this->error("Unable to get webhook info for {$botName}");

            return self::FAILURE;
        }

        $lastErrorDate = !\is_null($webhookInfo->last_error_date)
            ? \date('Y-m-d H:i:s', $webhookInfo->last_error_date) . ' UTC'
            : null;

        $lastSynchronizationErrorDate = !\is_null($webhookInfo->last_synchronization_error_date)
            ? \date('Y-m-d H:i:s', $webhookInfo->last_synchronization_error_date) . 'UTC'
            : null;

        $allowedUpdates = $webhookInfo->allowed_updates ?? [];

        $this->table(['Key', 'Value'])
            ->addRow(['bot_name', $botName])
            ->addRow(['url', $webhookInfo->url])
            ->addRow(['has_custom_certificate', $webhookInfo->has_custom_certificate ? 'true' : 'false'])
            ->addRow(['pending_update_count', $webhookInfo->pending_update_count])
            ->addRow(['ip_address', $webhookInfo->ip_address])
            ->addRow(['last_error_date', $lastErrorDate])
            ->addRow(['last_error_message', $webhookInfo->last_error_message])
            ->addRow(['last_synchronization_error_date', $lastSynchronizationErrorDate])
            ->addRow(['max_connections', $webhookInfo->max_connections])
            ->addRow(['allowed_updates', \implode(PHP_EOL, $allowedUpdates)])
            ->render();

        return self::SUCCESS;
    }
}
