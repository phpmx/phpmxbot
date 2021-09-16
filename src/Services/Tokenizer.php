<?php
declare(strict_types=1);

namespace PhpMx\Services;

class Tokenizer
{
    public function getUsersFromMessage(string $message): array
    {
        $increments = [];
        $decrements = [];

        preg_match_all('/(<@U[A-Z0-9]+>|\+\+|--)/', $message, $matches);
        $tokens = array_filter($matches[0]);

        $buffer = [];
        foreach ($tokens as $token) {
            if ('++' === $token) {
                $increments = array_merge($increments, $buffer);
                $buffer = [];
                continue;
            }

            if ('--' === $token) {
                $decrements = array_merge($decrements, $buffer);
                $buffer = [];
                continue;
            }

            $buffer[] = $token;
        }

        return [$increments, $decrements];
    }

    public function hasToken(string $token, array $tokens): bool
    {
        return in_array($token, $tokens);
    }

    public function excludeToken(string $token, array $tokens): array
    {
        return array_filter(
            $tokens,
            fn(string $identifier) => $identifier !== $token
        );
    }
}
