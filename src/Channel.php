<?php

namespace Events;

use Events\Interfaces\ChannelInterface;
use Events\Interfaces\EventInterface;
use Swoole\Coroutine\Channel as SwooleChannel;

class Channel implements ChannelInterface
{
    private SwooleChannel $channel;
    private bool $closed = false;

    public function __construct(int $size = 0)
    {
        $this->channel = new SwooleChannel($size);
    }

    public function push(EventInterface $event, int $timeout = -1): void
    {
        if ($this->closed) {
            throw new \RuntimeException('Cannot push to a closed channel');
        }

        $result = $this->channel->push($event, $timeout);
        if ($result === false) {
            throw new \RuntimeException('Failed to push event to channel');
        }
    }

    public function pop(): ?EventInterface
    {
        if ($this->closed) {
            return null;
        }

        $event = $this->channel->pop();
        return $event === false ? null : $event;
    }

    public function count(): int
    {
        return $this->channel->length();
    }

    public function close(): void
    {
        if (!$this->closed) {
            $this->channel->close();
            $this->closed = true;
        }
    }
}
