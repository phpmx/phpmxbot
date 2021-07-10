<?php

namespace PhpMx\Drivers;

use BotMan\BotMan\Drivers\Events\GenericEvent;
use BotMan\Drivers\Slack\SlackDriver;
use Tightenco\Collect\Support\Collection;

class CustomDriver extends SlackDriver
{
    public function hasMatchingEvent()
    {
        /** @var Collection */
        $payload = $this->payload;

        // The value of `callback_id` is configured in Slack when creating the shortcut.
        if ($payload->get('type') === 'message_action' && $payload->get('callback_id') === 'report_message') {
            $event = new GenericEvent($payload);
            $event->setName($payload->get('type'));

            return $event;
        }

        if ($payload->get('type') === 'event_callback' && $payload->get('event')['type'] === 'member_joined_channel') {
            $event = new GenericEvent($payload);
            $event->setName($payload->get('event')['type']);

            return $event;
        }

        if ($payload->get('type') === 'event_callback' && ($payload->get('event')['type'] === 'reaction_added' || $payload->get('event')['type'] === 'reaction_removed')) {
            $event = new GenericEvent($payload);
            $event->setName($payload->get('event')['type']);

            return $event;
        }

        parent::hasMatchingEvent();
    }
}
