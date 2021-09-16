<?php

namespace PhpMx\Services;

use Exception;

class GetRandomMessageByType
{
    public const INCREASED_POINTS = 'increased';
    public const DECREASED_POINTS = 'decreased';
    public const NOT_ALLOWED = 'not_allowed';

    public const MESSAGE_TYPES = [
        self::INCREASED_POINTS,
        self::DECREASED_POINTS,
        self::NOT_ALLOWED,
    ];

    private array $messages;

    public function __construct(array $increasedPointsMessages, array $decreasedPointsMessages, array $notAllowedMessages)
    {
        $this->messages = [
            self::INCREASED_POINTS => $increasedPointsMessages,
            self::DECREASED_POINTS => $decreasedPointsMessages,
            self::NOT_ALLOWED => $notAllowedMessages,
        ];
    }

    public function __invoke(string $type, ?array $replacements = null): string
    {
        if (!in_array($type, self::MESSAGE_TYPES)) {
            $exceptionMessage = sprintf(
                'The "%s" type provided does not match with any registered type, please ensure to provide one of the following types [%s].',
                $type,
                implode(', ', self::MESSAGE_TYPES)
            );

            throw new Exception($exceptionMessage);
        }

        $messages = $this->messages[$type];
        $randomIndex = array_rand($messages);
        $message = $messages[$randomIndex];

        if ($replacements) {
            $search = array_keys($replacements);
            $replace = array_values($replacements);

            $message = str_replace($search, $replace, $message);
        }

        return $message;
    }
}
