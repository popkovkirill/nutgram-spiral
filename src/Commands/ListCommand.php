<?php

declare(strict_types=1);

namespace Nutgram\Spiral\Commands;

use Nutgram\Spiral\Manager\BotManagerInterface;
use SergiX44\Nutgram\Handlers\Handler;
use SergiX44\Nutgram\Nutgram;
use Spiral\Console\Attribute\AsCommand;
use Spiral\Console\Attribute\Option;
use Spiral\Console\Command;

#[AsCommand(name: 'nutgram:list', description: 'List all registered handlers')]
final class ListCommand extends Command
{
    #[Option(name: 'name', shortcut: 'n')]
    private string|null $botName = null;

    public function __invoke(BotManagerInterface $botManager): int
    {
        $bot = $botManager->getBot($this->botName);
        $handlers = $this->getHandlers($bot);

        $handlers = \array_map(function (Handler $handler, string $signature) {
            return [
                $signature,
                $handler->getPattern(),
                $this->getCallableName($handler),
            ];
        }, $handlers, \array_keys($handlers));

        $this->table(['Signature', 'Pattern', 'Callable'], $handlers)
            ->render();

        $this->info('Bot webhook removed.');

        return self::SUCCESS;
    }

    protected function getCallableName(Handler $handler): string
    {
        // get callable value
        $callable = (fn() => $this->callable)->call($handler);

        // parse callable
        if (\is_string($callable)) {
            return \trim($callable);
        }

        if (\is_array($callable)) {
            if (\is_object($callable[0])) {
                return \sprintf("%s::%s", \get_class($callable[0]), \trim($callable[1]));
            }

            return \sprintf("%s::%s", \trim($callable[0]), \trim($callable[1]));
        }

        if ($callable instanceof \Closure) {
            return 'closure';
        }

        return 'unknown';
    }

    /**
     * @return array<string, Handler>
     */
    private function getHandlers(Nutgram $bot): array
    {
        /** @var array<string|int, mixed> $handlers */
        $handlers = (fn() => $this->handlers)->call($bot);

        return $this->dot($handlers);
    }

    /**
     * @param array<string|int, mixed> $handlers
     * @return array<string, Handler>
     */
    private function dot(array $handlers, ?string $path = null): array
    {
        $result = [];

        /**
         * @var string|int $key
         * @var array<string|int, mixed>|Handler $handler
         */
        foreach ($handlers as $key => $handler) {
            $handlerPath = \is_string($path)
                ? "$path.$key"
                : (string) $key;

            if (\is_array($handler)) {
                $result = [
                    ...$result,
                    ...$this->dot($handler, $handlerPath),
                ];

                continue;
            }

            $result[$handlerPath] = $handler;
        }

        return $result;
    }
}
