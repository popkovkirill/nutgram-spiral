<?php

declare(strict_types=1);

namespace Nutgram\Spiral\Commands;

use Nutgram\Spiral\Manager\BotManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SergiX44\Nutgram\RunningMode\SingleUpdate;
use Spiral\Console\Attribute\AsCommand;
use Spiral\Console\Attribute\Option;
use Spiral\Console\Command;

#[AsCommand(name: 'nutgram:run', description: 'Start the bot in long polling mode')]
final class RunCommand extends Command
{
    #[Option]
    private bool $once = false;

    #[Option(name: 'name', shortcut: 'n')]
    private string|null $botName = null;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(BotManagerInterface $botManager): int
    {
        $bot = $botManager->getBot($this->botName);

        if ($this->once) {
            $bot->setRunningMode(SingleUpdate::class);
        }

        $bot->run();

        return self::SUCCESS;
    }
}
