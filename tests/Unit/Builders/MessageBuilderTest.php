<?php

namespace PhpMx\Tests\Unit\Services;

use Exception;
use PhpMx\Builders\AdditionalParametersBuilder;
use PHPUnit\Framework\TestCase;

class ItTest extends TestCase
{
    public function testItShouldHaveAtLeastOneMessage(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Empty message, please add at least one element.');

        $additionalParametersBuilder = new AdditionalParametersBuilder();
        $additionalParametersBuilder->build();
    }

    public function testItShouldNotAddParagraphsToEmptyMessages(): void
    {
        $additionalParametersBuilder = AdditionalParametersBuilder::create()
            ->addParagraph();

        $this->assertSame(1, $additionalParametersBuilder->getParagraphCount());
    }

    public function testAddMarkdownShouldReturnAnInstanceOfAdditionalParametersBuilder(): void
    {
        $additionalParametersBuilder = AdditionalParametersBuilder::create()->addRow('Hello!');
        $this->assertInstanceOf(AdditionalParametersBuilder::class, $additionalParametersBuilder);
    }

    public function testItRetrieveTheExpectedParameters(): void
    {
        $expectedParameters = [
            'blocks' => '[{"type":"section","fields":[{"type":"mrkdwn","text":"Hello world!"},{"type":"mrkdwn","text":"This is the PhpMxBot"},{"type":"mrkdwn","text":"And I love the PhpMx community"}]},{"type":"section","fields":[{"type":"mrkdwn","text":"Hope to keep learning a lot"},{"type":"mrkdwn","text":"With you <3"}]}]'
        ];

        $additionalParameters = AdditionalParametersBuilder::create()
            ->addRow('Hello world!')
            ->addRow('This is the PhpMxBot')
            ->addRow('And I love the PhpMx community')
            ->addParagraph()
            ->addRow('Hope to keep learning a lot')
            ->addRow('With you <3')
            ->build();

        $this->assertSame($expectedParameters, $additionalParameters);
    }
}
