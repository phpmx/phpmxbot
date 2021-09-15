<?php

namespace PhpMx\Services;

use Exception;

class MessageBuilder
{
    const EMPTY_SECTION = [
        'type' => 'section',
        'fields' => []
    ];

    private int $currentSection = 0;
    private array $blocks = [self::EMPTY_SECTION];
    private int $maxSectionItems;

    public function __construct(int $maxSectionItems = 10)
    {
        $this->maxSectionItems = $maxSectionItems;
    }

    public static function create(int $maxSectionItems = 10): self
    {
        return new self($maxSectionItems);
    }

    public function addMarkdown(string $message): self
    {
        $this->addField([
            'type' => 'mrkdwn',
            'text' => $message,
        ]);

        return $this;
    }

    public function toAdditionalParameters(): array
    {
        $blocks = $this->blocksWithoutEmptySections();

        if (empty($blocks)) {
            throw new Exception('Empty message, please add at least one element.');
        }

        return [
            'blocks' => json_encode($blocks)
        ];
    }

    public function getSectionCount(): int
    {
        return count($this->blocks);
    }

    private function addSection(): void
    {
        $this->blocks[] = self::EMPTY_SECTION;

        $this->currentSection++;
    }

    private function addField(array $field): void
    {
        $fieldsCounter = count($this->blocks[$this->currentSection]['fields']);

        if ($fieldsCounter > 0 && $fieldsCounter % $this->maxSectionItems === 0) {
            $this->addSection();
        }

        $this->blocks[$this->currentSection]['fields'][] = $field;
    }

    private function blocksWithoutEmptySections(): array
    {
        return array_filter(
            $this->blocks,
            fn(array $section) => count($section['fields']) !== 0
        );
    }
}
