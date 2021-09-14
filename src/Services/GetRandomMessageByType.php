<?php

namespace PhpMx\Services;

use Exception;
use InvalidArgumentException;

class GetRandomMessageByType
{
    private array $types;
    private array $messages;

    public function __construct(array $messages)
    {
        $this->messages = $messages;
        $this->types = array_keys($messages);
    }

    /**
     * @throws Exception
     */
    public function __invoke(string $type): string
    {
        if (!in_array($type, $this->types)) {
            $message = sprintf(
                'Invalid "%s" message type, please provide one of the following values [%s].',
                $type,
                implode(', ', $this->types)
            );

            throw new Exception($message);
        }

        $randomIndex = array_rand($this->messages[$type]);

        return $this->messages[$type][$randomIndex];
    }

    public function getTypes(): array
    {
        return $this->types;
    }
}
