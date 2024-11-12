<?php

namespace Events\Interfaces;

interface EventStreamInterface extends SubscribableInterface, PublishableInterface
{
    public function getChannel(): ChannelInterface;
}
