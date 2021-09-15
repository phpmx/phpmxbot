<?php

namespace PhpMx\Builders;

use Exception;

class AdditionalParametersBuilder
{
    private array $blocks = [];

    public static function create(): self
    {
        return new self();
    }

    public function addText(string $message, bool $processEmoji = true): self
    {
        $this->blocks[] = [
            'type' => 'section',
            'text' => [
                'type' => 'plain_text',
                'text' => $message,
                'emoji' => $processEmoji
            ]
        ];

        return $this;
    }

    public function addMarkdown(string $message): self
    {
        $this->blocks[] = [
            'type' => 'section',
            'text' => [
                'type' => 'mrkdwn',
                'text' => $message
            ]
        ];

        return $this;
    }

    public function addDivider(): self
    {
        $this->blocks[] = [
            'type' => 'divider',
        ];

        return $this;
    }

    public function build(): array
    {
        return [
            'blocks' => json_encode($this->blocks)
        ];
    }
}
