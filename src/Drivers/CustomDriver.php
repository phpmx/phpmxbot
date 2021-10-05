<?php

namespace PhpMx\Drivers;

use BotMan\BotMan\Drivers\Events\GenericEvent;
use BotMan\Drivers\Slack\SlackDriver;
use Tightenco\Collect\Support\Collection;

class CustomDriver extends SlackDriver
{
    private const MESSAGE = 'message_action';
    private const REPORT = 'report_message';
    private const EVENT = 'event_callback';
    private const MEMBER_JOIN = 'member_joined_channel';

    public function hasMatchingEvent()
    {
        /** @var Collection */
        $payload = $this->payload;

        // The value of `callback_id` is configured in Slack when creating the shortcut.
        if ($payload->get('type') === self::MESSAGE && $payload->get('callback_id') === self::REPORT) {
            $event = new GenericEvent($payload);
            $event->setName($payload->get('type'));

            return $event;
        }

        if ($payload->get('type') === self::EVENT && $payload->get('event')['type'] === self::MEMBER_JOIN) {
            $event = new GenericEvent($payload);
            $event->setName($payload->get('event')['type']);

            return $event;
        }

        parent::hasMatchingEvent();
    }
}
