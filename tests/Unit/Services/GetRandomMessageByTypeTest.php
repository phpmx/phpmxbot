<?php

namespace PhpMx\Tests\Unit\Services;

use Exception;
use PhpMx\Services\GetRandomMessageByType;
use PHPUnit\Framework\TestCase;

class GetRandomMessageByTypeTest extends TestCase
{
    private array $increasedMessages = [
        'Increased message 1',
        'Increased message 2',
        'Increased message 3',
    ];
    private array $decreasedMessages = [
        'Decreased message 1',
        'Decreased message 2',
        'Decreased message 3',
    ];
    private array $notAllowedMessages = [
        'Not allowed message 1',
        'Not allowed message 2',
        'Not allowed message 3',
    ];

    private GetRandomMessageByType $getRandomMessageByType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->getRandomMessageByType = new GetRandomMessageByType(
            $this->increasedMessages,
            $this->decreasedMessages,
            $this->notAllowedMessages
        );
    }

    /**
     * @dataProvider getMessageTypesDataProvider
     */
    public function testItShouldSelectARandomMessage(string $type, array $possibleValues): void
    {
        $message = ($this->getRandomMessageByType)($type);

        $this->assertContains($message, $possibleValues);
    }

    public function getMessageTypesDataProvider(): array
    {
        return [
            [GetRandomMessageByType::INCREASED_POINTS, $this->increasedMessages],
            [GetRandomMessageByType::DECREASED_POINTS, $this->decreasedMessages],
            [GetRandomMessageByType::NOT_ALLOWED, $this->notAllowedMessages],
        ];
    }

    public function testItShouldThrowAnExceptionIfMessageTypeIsNotDefined(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'The "undefined_type" type provided does not match with any registered type, please ensure to provide one of the following types [increased, decreased, not_allowed].'
        );

        $type = 'undefined_type';
        ($this->getRandomMessageByType)($type);
    }
}
