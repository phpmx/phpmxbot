<?php

namespace PhpMx\Tests\Unit\Services;

use Exception;
use PhpMx\Services\GetRandomMessageByType;
use PhpMx\Services\SettingsRepository;
use PHPUnit\Framework\TestCase;

class GetRandomMessageByTypeTest extends TestCase
{
    private array $notAllowedMessages = [
        'Not allowed message 1',
        'Not allowed message 2',
        'Not allowed message 3',
    ];

    private GetRandomMessageByType $getRandomMessageByType;

    protected function setUp(): void
    {
        parent::setUp();

        $settingsRepository = $this->createMock(SettingsRepository::class);
        $this->getRandomMessageByType = new GetRandomMessageByType($settingsRepository);
    }

    public function testItShouldSelectARandomMessage(): void
    {
        $settingsRepository = $this->createMock(SettingsRepository::class);
        $settingsRepository->expects($this->any())
            ->method('getJsonSetting')
            ->willReturn($this->notAllowedMessages);

        $getRandomMessageByType = new GetRandomMessageByType($settingsRepository);

        $message = $getRandomMessageByType(GetRandomMessageByType::NOT_ALLOWED);

        $this->assertContains($message, $this->notAllowedMessages);
    }

    public function testItShouldThrowAnExceptionIfMessageTypeIsNotDefined(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'The "undefined_type" type provided does not match with any registered type, please ensure to provide one of the following types [points_increased_messages, points_decreased_messages, points_not_allowed_messages].'
        );

        $type = 'undefined_type';
        ($this->getRandomMessageByType)($type);
    }

    public function testItShouldReplaceValues(): void
    {
        $user = "<@USRS8912>";
        $score = 25;
        $increasedMessages = ['Hi {user}!, your have {score} points.'];

        $expectedMessage = 'Hi <@USRS8912>!, your have 25 points.';

        $settingsRepository = $this->createMock(SettingsRepository::class);
        $settingsRepository->expects($this->any())
            ->method('getJsonSetting')
            ->with(GetRandomMessageByType::INCREASED_POINTS)
            ->willReturn($increasedMessages);

        $getRandomMessage = new GetRandomMessageByType($settingsRepository);
        $message = $getRandomMessage(GetRandomMessageByType::INCREASED_POINTS, [
            '{user}' => $user,
            '{score}' => $score,
        ]);

        $this->assertSame($expectedMessage, $message);
    }
}
