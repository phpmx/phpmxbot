<?php

namespace PhpMx\Conversation;

use BotMan\BotMan\BotMan;
use Tightenco\Collect\Support\Collection;

class Report implements ConversationInterface
{
    public function __invoke(Collection $event, BotMan $botman)
    {
        // Send a DM to a user when a report shortcut is called on a message.
        $botman->say(
            "Tu reporte ha sido recibido. Nos pondremos en contacto a la brevedad.",
            $event->get('user')['id']
        );
    }

    public function subscribe(BotMan $botman)
    {
        $botman->on('message_action', $this);
    }
}
