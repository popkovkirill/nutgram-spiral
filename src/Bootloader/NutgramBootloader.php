<?php

declare(strict_types=1);

namespace Nutgram\Spiral\Bootloader;

use Nutgram\Spiral\Config\Bot;
use Nutgram\Spiral\Manager\BotFactory;
use Nutgram\Spiral\Manager\BotManager;
use Nutgram\Spiral\Manager\BotManagerInterface;
use SergiX44\Nutgram\Nutgram;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\EnvironmentInterface;
use Spiral\Config\ConfiguratorInterface;
use Nutgram\Spiral\Commands;
use Nutgram\Spiral\Config\NutgramConfig;
use Spiral\Console\Bootloader\ConsoleBootloader;

final class NutgramBootloader extends Bootloader
{
    protected const array SINGLETONS = [
        Nutgram::class => [self::class, 'initNutgram'],
        BotManagerInterface::class => [self::class, 'initBotManager'],
    ];

    public function __construct(
        private readonly ConfiguratorInterface $config,
    ) {}

    public function init(ConsoleBootloader $console, EnvironmentInterface $env): void
    {
        $this->initConfig($env);

        $console->addCommand(Commands\HookInfoCommand::class);
        $console->addCommand(Commands\HookRemoveCommand::class);
        $console->addCommand(Commands\HookSetCommand::class);
        $console->addCommand(Commands\ListCommand::class);
        $console->addCommand(Commands\RegisterCommandsCommand::class);
        $console->addCommand(Commands\RunCommand::class);
    }

    private function initConfig(EnvironmentInterface $env): void
    {
        $this->config
            ->setDefaults(NutgramConfig::CONFIG, [
                'default_bot' => 'default',
                'webhook_host' => $env->get('NUTGRAM_WEBHOOK_HOST', 'https://localhost'),
                'bots' => [
                    'default' => new Bot(
                        token: (string) $env->get('NUTGRAM_BOT_TOKEN'),
                        logChannel: (string) $env->get('MONOLOG_DEFAULT_CHANNEL', 'null'),
                        cacheStorage: (string) $env->get('NUTGRAM_BOT_CACHE', 'local'),
                    ),
                ],
            ]);
    }

    private function initNutgram(BotManagerInterface $botManager): Nutgram
    {
        return $botManager->getBot();
    }

    private function initBotManager(BotFactory $factory, NutgramConfig $config): BotManager
    {
        return new BotManager($factory, $config->defaultBot());
    }
}
