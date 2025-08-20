<?php

declare(strict_types=1);

namespace Nutgram\Spiral\Commands;

use Nutgram\Spiral\Manager\BotManagerInterface;
use Spiral\Console\Attribute\AsCommand;
use Spiral\Console\Attribute\Option;
use Spiral\Console\Command;

#[AsCommand(name: 'nutgram:register-commands', description: 'Register the bot commands')]
final class RegisterCommandsCommand extends Command
{
    #[Option(name: 'name', shortcut: 'n')]
    private string|null $botName = null;

    public function __invoke(BotManagerInterface $botManager): int
    {
        $botManager->getBot($this->botName)
            ->registerMyCommands();

        $this->info('Bot commands set.');

        return self::SUCCESS;
    }
}
