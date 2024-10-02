<?php

declare(strict_types=1);

namespace Szymon\NodeParser\NodeJsonParser;

use App\Service\NodeJsonParser\Node\Node;
use App\Service\NodeJsonParser\Node\TextNode;

class NodesToJsonConverter
{
    /**
     * @param Node[] $nodes
     */
    public function convert(array $nodes): string
    {
        return json_encode($this->convertNodes($nodes));
    }

    /**
     * @param Node[] $nodes
     * @return array[]
     */
    private function convertNodes(array $nodes): array
    {
        return array_map(
            fn (Node $node) => $this->convertNode($node),
            $nodes,
        );
    }

    private function convertNode(Node $node): array
    {
        if (get_class($node) === TextNode::class) {
            return $this->convertTextNode($node);
        }

        return [
            'type' => $node->getType()->value,
            'attributes' => $node->getAttributes(),
            'children' => $this->convertNodes($node->getChildren()),
        ];
    }

    private function convertTextNode(TextNode $node): array
    {
        return [
            'type' => 'text',
            'value' => $node->getValue(),
        ];
    }
}
