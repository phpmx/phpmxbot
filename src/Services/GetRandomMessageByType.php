<?php

namespace PhpMx\Services;

use Exception;
use InvalidArgumentException;

class GetRandomMessageByType
{
    private array $types;
    private array $messages;

    public function __construct(array $types, array $messages)
    {
        $this->validateTypes($types);
        $this->validateMessages($types, $messages);

        $this->types = $types;
        $this->messages = $messages;
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

        return array_rand($this->messages[$type]);
    }

    private function validateTypes(array $types): void
    {
        if (empty($types)) {
            throw new InvalidArgumentException('The message types cannot be empty, please add at least one entry.');
        }
    }

    private function validateMessages(array $types, array $messages): void
    {
        if (!in_array($types, array_keys($messages))) {
            $message = sprintf(
                'The messages array should contain one key for each message type, please add a set of messages for the following types [%s].',
                implode(', ', $types)
            );
            throw new InvalidArgumentException($message);
        }
    }
}
