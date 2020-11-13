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

    public function arrayToBlocks($array)
    {
        $blocks = [$this->getEmptySection()];

        $section = 0;
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
