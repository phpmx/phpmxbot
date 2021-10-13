<?php

namespace PhpMx\Conversation;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use PhpMx\Builders\AdditionalParametersBuilder;
use PhpMx\Services\GetRandomMessageByType;
use PhpMx\Services\Leaderboard;
use PhpMx\Services\Message;
use PhpMx\Services\Tokenizer;
use Tightenco\Collect\Support\Collection;

class PlusPlus implements ConversationInterface
{
    private Tokenizer $tokenizer;
    private Leaderboard $leaderboard;
    private Message $message;
    private GetRandomMessageByType $getRandomMessageByType;

    public function __construct(
        Tokenizer $tokenizer,
        Leaderboard $leaderboard,
        Message $message,
        GetRandomMessageByType $getRandomMessageByType
    ) {
        $this->tokenizer = $tokenizer;
        $this->leaderboard = $leaderboard;
        $this->message = $message;
        $this->getRandomMessageByType = $getRandomMessageByType;
    }

    public function handleMessage(BotMan $bot)
    {
        $msg = $bot->getMessage()->getText();
        list($increments, $decrements) = $this->tokenizer->getUsersFromMessage($msg);
        $payload = $bot->getMessage()->getPayload();
        $replyParameters = new AdditionalParametersBuilder();

        $user = $payload['user'] ?? 'unknown';
        $userToken = "<@$user>";

        if ($user !== 'unknown' && $this->tokenizer->hasToken($userToken, $increments)) {
            $increments = $this->tokenizer->excludeToken($userToken, $increments);
            $message = ($this->getRandomMessageByType)(GetRandomMessageByType::NOT_ALLOWED, ['{user}' => $userToken]);
            $replyParameters->addMarkdown($message)->addDivider();
        }

        $points = $this->leaderboard->updatePoints($user, [$increments,$decrements]);

        foreach ($points as $user => $score) {
            $type = in_array($user, $increments)
                ? GetRandomMessageByType::INCREASED_POINTS
                : GetRandomMessageByType::DECREASED_POINTS;

            $message = ($this->getRandomMessageByType)($type, ['{user}' => $user, '{score}' => $score]);
            $replyParameters->addMarkdown($message);
        }

        $bot->replyInThread('Points updated!', $replyParameters->build());
    }

    public function handleReactionAdded($payload, BotMan $bot)
    {
        $this->handleReactions($payload, $bot, true);
    }

    public function handleReactionRemoved($payload, BotMan $bot)
    {
        $this->handleReactions($payload, $bot, false);
    }

    public function handleReactions($payload, BotMan $bot, bool $added)
    {
        $event = json_decode($payload, true);
        $points = $this->leaderboard->reaction($event, $added);

        $matchingMessage = new IncomingMessage(
            "",
            $event['item_user'],
            $event['item']['channel'],
            Collection::make($event['item'])
        );
        $bot->replyInThread('Points updated!', $this->message->arrayToBlocks($points), $matchingMessage, $bot);
    }


    public function subscribe(BotMan $botman)
    {
        $botman->hears('.*(\+\+|\-\-).*', array($this, 'handleMessage'));
        $botman->on('reaction_added', array($this, 'handleReactionAdded'));
        $botman->on('reaction_removed', array($this, 'handleReactionRemoved'));
    }
}
