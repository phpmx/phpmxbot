<?php

declare(strict_types=1);

namespace PhpMx\Tests\Unit\Conversation;

use BotMan\BotMan\BotMan;
use PhpMx\Conversation\Greeter;
use PhpMx\Services\Greeter as ServicesGreeter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;

final class GreeterTest extends TestCase
{
    public function testSubscribe()
    {
        $botmanMock = $this->createMock(BotMan::class);
        $serviceMock = $this->createMock(ServicesGreeter::class);

        $greeter = new Greeter($serviceMock);

        $botmanMock->expects($this->once())
            ->method('on')
            ->with('member_joined_channel', [$greeter, 'handleEvent']);

        $greeter->subscribe($botmanMock);
    }

    public function provideRenderMessages()
    {
        $event = [
            'user' => 'foo',
            'channel' => 'bar',
        ];

        return [
            'plain text' => [
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                $event,
            ],
            'only user' => [
                'Lorem ipsum <@foo> dolor sit amet, consectetur adipiscing elit.',
                'Lorem ipsum {user} dolor sit amet, consectetur adipiscing elit.',
                $event,
            ],
            'only channel' => [
                'Lorem ipsum dolor sit amet, <#bar> consectetur adipiscing elit.',
                'Lorem ipsum dolor sit amet, {channel} consectetur adipiscing elit.',
                $event,
            ],
            'user and channel' => [
                'Lorem <@foo> ipsum dolor sit amet, <#bar> consectetur adipiscing elit.',
                'Lorem {user} ipsum dolor sit amet, {channel} consectetur adipiscing elit.',
                $event,
            ],
        ];
    }

    /**
     * @dataProvider provideRenderMessages
     */
    public function testRenderMessage($expected, $message, $event)
    {
        $this->assertEquals($expected, Greeter::renderMessage($message, $event));
    }

    public function provideGreeterMessages(): array
    {
        $user = 'UFOO';
        $channel = 'CBAR';
        $parameterBag = new ParameterBag([
            'event' => [
                'user' => $user,
                'channel' => $channel,
            ]
        ]);

        return [
            'no messages' => [
                $parameterBag,
                []
            ],
            'plain text message' => [
                $parameterBag,
                [
                    [
                        'message' => 'This is a test',
                        'method' => ''
                    ]
                ]
            ],
        ];
    }

    /**
     * @dataProvider provideGreeterMessages
     */
    public function testHandleEvent(ParameterBag $payload, $messages)
    {
        $serviceMock = $this->createMock(ServicesGreeter::class);
        $serviceMock->expects($this->once())
            ->method('getMessagesFor')
            ->with($payload->get('event')['channel'])
            ->willReturn($messages);

        $botmanMock = $this->createMock(BotMan::class);
        $botmanMock->expects($this->exactly(count($messages)))->method('say');

        $greeter = new Greeter($serviceMock);
        $greeter->handleEvent($payload, $botmanMock);
    }
}
