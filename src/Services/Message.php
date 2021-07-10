<?php

namespace PhpMx\Services;

class Message
{
    private function getEmptySection()
    {
        return [
            'type' => 'section',
            'fields' => []
        ];
    }

    private function getTextSection($text)
    {
        return [
            'type' => 'section',
            'text' => [
                'type' => 'mrkdwn',
                'text' => $text
            ]
        ];
    }

    public function arrayToBlocks($array, $title = '')
    {
        $section = 0;
        $blocks = [];

        if (!empty($title)) {
            $blocks[] = $this->getTextSection($title);
            $section++;
        }

        $blocks[] = $this->getEmptySection();

        foreach ($array as $key => $value) {
            if (count($blocks[$section]['fields']) === 10) {
                $blocks[] = $this->getEmptySection();
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
