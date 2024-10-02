<?php

declare(strict_types=1);

namespace Szymon\NodeParser\NodeJsonParser\Node;

class TextNode extends Node
{
    public function __construct(
        private readonly string $value,
    ) {
        parent::__construct(NodeType::TextNode, [], []);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
