<?php

namespace PhpMx\Tests\Unit\Services;

use Exception;
use PhpMx\Builders\AdditionalParametersBuilder;
use PHPUnit\Framework\TestCase;

class AdditionalParametersBuilderTest extends TestCase
{
    public function testItShouldHaveAtLeastOneMessage(): void
    {
        $expectedParameters = [
            'blocks' => '[]'
        ];

        $additionalParametersBuilder = new AdditionalParametersBuilder();
        $additionalParameters = $additionalParametersBuilder->build();

        $this->assertSame($expectedParameters, $additionalParameters);
    }

    public function testAddMarkdownShouldReturnAnInstanceOfAdditionalParametersBuilder(): void
    {
        $additionalParametersBuilder = AdditionalParametersBuilder::create()->addText('Hello!');
        $this->assertInstanceOf(AdditionalParametersBuilder::class, $additionalParametersBuilder);
    }

    public function testItRetrieveTheExpectedParameters(): void
    {
        $expectedParameters = [
            'blocks' => '[{"type":"section","text":{"type":"plain_text","text":"Hello world!","emoji":true}},{"type":"section","text":{"type":"plain_text","text":"This is the PhpMxBot","emoji":true}},{"type":"section","text":{"type":"plain_text","text":"And I love the PhpMx community","emoji":true}},{"type":"divider"},{"type":"section","text":{"type":"plain_text","text":"Hope to keep learning a lot","emoji":true}},{"type":"section","text":{"type":"mrkdwn","text":"*With you* :heart:"}}]'
        ];

        $additionalParameters = AdditionalParametersBuilder::create()
            ->addText('Hello world!')
            ->addText('This is the PhpMxBot')
            ->addText('And I love the PhpMx community')
            ->addDivider()
            ->addText('Hope to keep learning a lot')
            ->addMarkdown('*With you* :heart:')
            ->build();

        $this->assertSame($expectedParameters, $additionalParameters);
    }
}
