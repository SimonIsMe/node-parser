<?php

declare(strict_types=1);

namespace Szymon\NodeParser\NodeJsonParser;

class NodeAttributeValidator
{
    public function __construct(
        private readonly string $attributeName,
        private readonly bool $isRequired,
        private readonly string $regExp,
        private readonly array $prefixes,
        private readonly array $suffixes,
    ) {
    }

    public function getAttributeName(): string
    {
        return $this->attributeName;
    }

    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    public function isValid(string $attributeValue): bool
    {
        return $this->isValidRegExp($attributeValue)
            && $this->isValidPrefix($attributeValue)
            && $this->isValidSuffix($attributeValue);
    }

    private function isValidRegExp(string $attributeValue): bool
    {
        $matches = [];
        return preg_match($this->regExp, $attributeValue, $matches) === 1;
    }

    private function isValidPrefix(string $attributeValue): bool
    {
        if (empty($this->prefixes)) {
            return true;
        }

        foreach ($this->prefixes as $prefix) {
            if (str_starts_with($attributeValue, $prefix)) {
                return true;
            }
        }

        return false;
    }

    private function isValidSuffix(string $attributeValue): bool
    {
        if (empty($this->suffixes)) {
            return true;
        }

        foreach ($this->suffixes as $suffix) {
            if (str_ends_with($attributeValue, $suffix)) {
                return true;
            }
        }

        return false;
    }
}
