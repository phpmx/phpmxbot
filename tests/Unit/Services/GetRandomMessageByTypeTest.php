<?php

namespace PhpMx\Tests\Unit\Services;

use Exception;
use InvalidArgumentException;
use PhpMx\Services\GetRandomMessageByType;
use PHPUnit\Framework\TestCase;

class GetRandomMessageByTypeTest extends TestCase
{
    private array $messages = [
        'points_added' => [
            'Points added message 1',
            'Points added message 2',
            'Points added message 3',
        ],
        'points_removed' => [
            'Points removed message 1',
            'Points removed message 2',
            'Points removed message 3',
        ],
        'points_restricted' => [
            'Points restricted message 1',
            'Points restricted message 2',
            'Points restricted message 3',
        ],
    ];

    private GetRandomMessageByType $getRandomMessageByType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->getRandomMessageByType = new GetRandomMessageByType($this->messages);
    }

    public function testItShouldSelectARandomMessage(): void
    {
        $type = $this->getRandomMessageByType->getTypes()[0];
        $possibleMessages = $this->messages[$type];

        $message = ($this->getRandomMessageByType)($type);

        $this->assertContains($message, $possibleMessages);
    }

    public function testItShouldThrowAnExceptionIfMessageTypeIsNotDefined(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'Invalid "undefined_type" message type, please provide one of the following values [points_added, points_removed, points_restricted].'
        );

        $type = 'undefined_type';
        ($this->getRandomMessageByType)($type);
    }
}
