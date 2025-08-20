<?php

declare(strict_types=1);

namespace Nutgram\Spiral\Http;

use Psr\Http\Message\ServerRequestInterface;
use SergiX44\Nutgram\RunningMode\Webhook;

final class HttpWebhookMode extends Webhook
{
    public function __construct(protected ServerRequestInterface $request)
    {
        parent::__construct(static fn() => 'success', 'success');
    }

    #[\Override]
    protected function input(): ?string
    {
        return (string) $this->request->getBody();
    }
}
