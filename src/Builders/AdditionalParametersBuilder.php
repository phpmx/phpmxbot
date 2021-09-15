<?php

namespace PhpMx\Builders;

use Exception;

class AdditionalParametersBuilder
{
    const EMPTY_SECTION = [
        'type' => 'section',
        'fields' => []
    ];

    private int $currentSection = 0;
    private array $blocks = [self::EMPTY_SECTION];

    public static function create(): self
    {
        return new self();
    }

    public function addRow(string $message): self
    {
        $this->addField([
            'type' => 'mrkdwn',
            'text' => $message,
        ]);

        return $this;
    }

    public function addParagraph(): self
    {
        $fieldCounter = count($this->blocks[$this->currentSection]['fields']);

        if ($fieldCounter !== 0) {
            $this->addSection();
        }

        return $this;
    }

    public function build(): array
    {
        $blocks = $this->blocksWithoutEmptySections();

        if (empty($blocks)) {
            throw new Exception('Empty message, please add at least one element.');
        }

        return [
            'blocks' => json_encode($blocks)
        ];
    }

    public function getParagraphCount(): int
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
