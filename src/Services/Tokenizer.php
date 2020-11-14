<?php

namespace PhpMx\Services;

class Tokenizer
{
    public function getUsersFromMessage(string $msg): array
    {
        $msg = trim($msg);

        // Add spaces around ++ and -- just in case someone forgot to add them
        $msg = implode(' ++ ', explode('++', $msg));
        $msg = implode(' -- ', explode('--', $msg));

        // Remove all unknown characters
        $msg = preg_replace('/[^a-z0-9_@:#<>| \-+]/i', '', $msg);

        // Replace 1+ consecutive spaces with single commas
        $msg = preg_replace('/\s+/i', ',', $msg);

        // Break string into an array of tokens
        $msg = explode(',', $msg);

        $inc = [];
        $dec = [];
        $buffer = [];

        while ($token = array_shift($msg)) {
            if ($token !== '++' && $token !== '--') {
                $buffer[] = $token;
                continue;
            }

            if ($token === '++') {
                $inc = array_merge($inc, $buffer);
            } else {
                $dec = array_merge($dec, $buffer);
            }

            $buffer = [];
        }

        return [$inc, $dec];
    }
}
