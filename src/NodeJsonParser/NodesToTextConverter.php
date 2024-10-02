<?php

declare(strict_types=1);

namespace Szymon\NodeParser\NodeJsonParser;

use Szymon\NodeParser\NodeJsonParser\Node\Node;
use Szymon\NodeParser\NodeJsonParser\Node\NodeType;
use Szymon\NodeParser\NodeJsonParser\Node\TextNode;

class NodesToTextConverter
{
    /**
     * @var NodeType[]
     */
    private const array BLOCK_NODES = [
        NodeType::H1,
        NodeType::H2,
        NodeType::P,
        NodeType::OL,
        NodeType::UL,
        NodeType::LI,
        NodeType::Blockquote,
        NodeType::Pre,
    ];

    /**
     * @param Node[] $nodes
     */
    public function convertToText(array $nodes): string
    {
        return implode(
            '',
            array_map(
                fn (Node $node) => $this->convertNodeToText($node),
                $nodes,
            ),
        );
    }

    private function convertNodeToText(Node $node): string
    {
        if ($node->getType() === NodeType::TextNode) {
            /** @var TextNode $node */
            return $node->getValue();
        }

        $childrenNodesConvertedToText = $this->convertToText($node->getChildren());
        if (in_array($node->getType(), self::BLOCK_NODES)) {
            return "\n" . $childrenNodesConvertedToText . "\n";
        }

        return $childrenNodesConvertedToText;
    }
}