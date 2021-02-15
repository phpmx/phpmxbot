<?php

namespace PhpMx\Conversation;

use BotMan\BotMan\BotMan;
use PhpMx\Conversation\ConversationInterface;
use PhpMx\Services\Greeter as GreeterService;
use Symfony\Component\HttpFoundation\ParameterBag;

class Greeter implements ConversationInterface
{
    private $greeterService;

    public function __construct(GreeterService $greeterService)
    {
        $this->greeterService = $greeterService;
    }

    public function handleEvent(ParameterBag $payload, BotMan $bot)
    {
        $event = $payload->get('event');
        $channel = $event['channel'];
        $user = $event['user'];

        $messages = $this->greeterService->getMessagesFor($channel);

        $additionalParameters = [
            'link_names' => true,
            'unfurl_links' => true,
            'unfurl_media' => true,
        ];

        foreach ($messages as $message) {
            $text = $this->renderMessage($message['message'], $event);
            $to = $message['method'] === 'channel' ? $channel : $user;
            $bot->say($text, $to, null, $additionalParameters);
        }
    }

    public static function renderMessage($message, $event)
    {
        $channel = $event['channel'];
        $user = $event['user'];

        return str_replace(['{user}', '{channel}'], ["<@{$user}>", "<#{$channel}>"], $message);
    }

    public function subscribe(BotMan $botman)
    {
        $botman->on('member_joined_channel', [$this, 'handleEvent']);
    }
}
