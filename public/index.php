<?php

require_once __DIR__ . '/vendor/autoload.php';

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\Drivers\Slack\SlackDriver;
use PhpMx\PlusPlusHandler;
use PhpMx\MessageHandler;
use Tightenco\Collect\Support\Collection;

DriverManager::loadDriver(SlackDriver::class);

$config = parse_ini_file(__DIR__ . '/config.ini', true);
$botman = BotManFactory::create($config);

// TODO: Move all handlers to their own file/class
$botman->hears('.*(\+\+|\-\-).*', function (BotMan $bot) {
    $msg = $bot->getMessage()->getText();
    $tokens = PlusPlusHandler::tokenize($msg);

    $payload = $bot->getMessage()->getPayload();
    $user = $payload['user'] ?? 'unknown';
    $points = PlusPlusHandler::updatePoints($user, $tokens);

    $bot->replyInThread('Points updated!', MessageHandler::arrayToBlocks($points));
});

$botman->hears('leaderboard', function (BotMan $bot) {
    $count = 10;
    $points = PlusPlusHandler::leaderboard($count);

    $bot->replyInThread('Top Leaderboard', MessageHandler::arrayToBlocks($points));
});

function handleReaction($payload, BotMan $bot, $added)
{
    $event = json_decode($payload, true);
    $points = PlusPlusHandler::reaction($event, $added);

    $matchingMessage = new IncomingMessage("", $event['item_user'], $event['item']['channel'], Collection::make($event['item']));
    $bot->replyInThread('Points updated!', MessageHandler::arrayToBlocks($points), $matchingMessage, $bot);
}

$botman->on('reaction_added', function ($payload, BotMan $bot) {
    handleReaction($payload, $bot, true);
});

$botman->on('reaction_removed', function ($payload, BotMan $bot) {
    handleReaction($payload, $bot, false);
});

$botman->listen();
