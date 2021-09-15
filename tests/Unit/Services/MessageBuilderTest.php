<?php

namespace PhpMx\Tests\Unit\Services;

use Exception;
use PhpMx\Services\MessageBuilder;
use PHPUnit\Framework\TestCase;

class MessageBuilderTest extends TestCase
{
    public function testMessageBuilderShouldHaveAtLeastOneMessage(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Empty message, please add at least one element.');

        $messageBuilder = new MessageBuilder();
        $messageBuilder->toAdditionalParameters();
    }

    public function testAddMarkdownShouldReturnAnInstanceOfMessageBuilder(): void
    {
        $messageBuilder = MessageBuilder::create()->addMarkdown('Hello!');
        $this->assertInstanceOf(MessageBuilder::class, $messageBuilder);
    }

    public function testMessageBuilderShouldCreateSectionsAfterRebaseMaxSectionItems(): void
    {
        $messageBuilder = MessageBuilder::create(2)
            ->addMarkdown('Hello!')
            ->addMarkdown('world')
            ->addMarkdown('!!!')
            ->addMarkdown('I am')
            ->addMarkdown('the PHPmx')
            ->addMarkdown('PlusPlus bot');

        $this->assertSame(3, $messageBuilder->getSectionCount());
    }

    public function testMessageBuilderRetrieveTheExpectedParameters(): void
    {
        $expectedParameters = [
            'blocks' => '[{"type":"section","fields":[{"type":"mrkdwn","text":"Hello world!"},{"type":"mrkdwn","text":"This is the PhpMxBot"},{"type":"mrkdwn","text":"And I love the PhpMx community"}]},{"type":"section","fields":[{"type":"mrkdwn","text":"Hope to keep learning a lot"},{"type":"mrkdwn","text":"With you <3"}]}]'
        ];

        $additionalParameters = MessageBuilder::create(3)
            ->addMarkdown('Hello world!')
            ->addMarkdown('This is the PhpMxBot')
            ->addMarkdown('And I love the PhpMx community')
            ->addMarkdown('Hope to keep learning a lot')
            ->addMarkdown('With you <3')
            ->toAdditionalParameters();

        $this->assertSame($expectedParameters, $additionalParameters);
    }
}
