<?php

declare(strict_types=1);

namespace PhpMx\Tests\Unit\Services;

use PhpMx\Services\Tokenizer;
use PHPUnit\Framework\TestCase;

final class TokenizerTest extends TestCase
{
    public function providerTokenizerCreateExpectedTokens(): array
    {
        return [
            'empty' => ['', [], []],
            'simple increment' => ['<@UFOO>++', ['<@UFOO>'], []],
            'simple decrement' => ['<@UFOO>--', [], ['<@UFOO>']],
            'double increment' => ['<@UFOO> <@UBAR> ++', ['<@UFOO>', '<@UBAR>'], []],
            'double decrement' => ['<@UFOO> <@UBAR> --', [], ['<@UFOO>', '<@UBAR>']],
            'mixed' => [
                '<@UI1> <@UI2> ++ <@UD1> <@UD2> -- <@UI3> <@UI4> ++ <@UX1>',
                ['<@UI1>', '<@UI2>', '<@UI3>', '<@UI4>'],
                ['<@UD1>', '<@UD2>'],
            ],
            'duplicated increment' => ['<@UFOO>++ <@UFOO>++', ['<@UFOO>', '<@UFOO>'], []],
            'duplicated inc dec inc' => ['<@UFOO>++ <@UFOO>-- <@UFOO>++', ['<@UFOO>', '<@UFOO>'], ['<@UFOO>']],
            'duplicated dec inc dec' => ['<@UFOO>-- <@UFOO>++ <@UFOO>--', ['<@UFOO>'], ['<@UFOO>', '<@UFOO>']],
            'not user' => ['@something ++', [], []],
            'exclude' => ['++ <@UFOO>', [], []],
        ];
    }

    /**
     * @param string $message
     * @param array $increment
     * @param array $decrement
     * @dataProvider providerTokenizerCreateExpectedTokens
     */
    public function testTokenizerCreateExpectedTokens(string $message, array $increment, array $decrement): void
    {
        $tokenizer = new Tokenizer();
        $tokens = $tokenizer->getUsersFromMessage($message);
        $this->assertEquals($tokens, [$increment, $decrement]);
    }
}
