<?php

namespace PhpMx\Conversation;

use BotMan\BotMan\BotMan;
use PhpMx\Builders\AdditionalParametersBuilder;

class Hello implements ConversationInterface
{
    public function __invoke(BotMan $bot)
    {
        $lastMessage = <<<TEXT


Hola mundo!     :D

Solo estoy probando los formatos de texto,
como las ~cursivas~ o *negitas*

y definitivamente los espacios :D
TEXT;

        $additionalParameters = AdditionalParametersBuilder::create()
            ->addText('Hoy es 15 de Septiembre!!')
            ->addText('El pueblo esta que arde...')
            ->addText('El coronavirus me duele en el pecho')
            ->addMarkdown('\nYo solo quiero progrmar! \n*XD*')
            ->addMarkdown("\nYo solo quiero progrmar! \n*XD*")
            ->addMarkdown($lastMessage)
            ->build();

        $bot->replyInThread('', $additionalParameters);
    }

    public function subscribe(BotMan $bot)
    {
        $bot->hears('Love programming', $this);
    }
}
