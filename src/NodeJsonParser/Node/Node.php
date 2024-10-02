<?php

declare(strict_types=1);

namespace Szymon\NodeParser\NodeJsonParser\Node;

class Node
{
    /**
     * @param NodeType $type
     * @param string[string] $attributes
     * @param Node[] $children
     */
    public function __construct(
        readonly private NodeType $type,
        readonly private array $attributes,
        readonly private array $children,
    ) {
    }

    public function getType(): NodeType
    {
        return $this->type;
    }

    /**
     * @return string[string]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return Node[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }
}
