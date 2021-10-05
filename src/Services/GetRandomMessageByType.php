<?php

namespace PhpMx\Services;

use Exception;

class GetRandomMessageByType
{
    public const INCREASED_POINTS = 'points_increased_messages';
    public const DECREASED_POINTS = 'points_decreased_messages';
    public const NOT_ALLOWED = 'points_not_allowed_messages';

    public const MESSAGE_TYPES = [
        self::INCREASED_POINTS,
        self::DECREASED_POINTS,
        self::NOT_ALLOWED,
    ];

    private SettingsRepository $settingsRepository;

    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * @param string[][]|null $replacements
     *
     * @throws Exception
     */
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

        $messages = $this->settingsRepository->getJsonSetting($type);
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
