<?php

namespace PhpMx\Handlers;

class Message
{
    private static function getEmptySection()
    {
        return [
            'type' => 'section',
            'fields' => []
        ];
    }

    public static function arrayToBlocks($array)
    {
        $blocks = [self::getEmptySection()];

        $section = 0;
        foreach ($array as $key => $value) {
            if (count($blocks[$section]['fields']) === 10) {
                $blocks[] = self::getEmptySection();
                $section++;
            }

            $blocks[$section]['fields'][] = [
                'type' => 'mrkdwn',
                'text' => "*$key*: $value",
            ];
        }

        $parameters = [
            'blocks' => json_encode($blocks)
        ];

        return $parameters;
    }
}
