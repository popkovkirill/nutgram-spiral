<?php

declare(strict_types=1);

namespace Nutgram\Spiral\Commands;

use GuzzleHttp\Exception\GuzzleException;
use Nutgram\Spiral\Manager\BotManagerInterface;
use SergiX44\Nutgram\Telegram\Exceptions\TelegramException;
use Spiral\Console\Attribute\AsCommand;
use Spiral\Console\Attribute\Option;
use Spiral\Console\Command;

#[AsCommand(name: 'nutgram:hook:remove', description: 'Remove the bot webhook')]
final class HookRemoveCommand extends Command
{
    #[Option(shortcut: 'd')]
    private bool $dropPendingUpdates = false;

    #[Option(name: 'name', shortcut: 'n')]
    private string|null $botName = null;

    /**
     *
     * @throws GuzzleException
     * @throws \JsonException
     * @throws TelegramException
     */
    public function __invoke(BotManagerInterface $botManager): int
    {
        $bot = $botManager->getBot($this->botName);

        $bot->deleteWebhook($this->dropPendingUpdates);

        if ($this->dropPendingUpdates) {
            $this->info('Pending updates dropped.');
        }

        $this->info('Bot webhook removed.');

        return self::SUCCESS;
    }
}
