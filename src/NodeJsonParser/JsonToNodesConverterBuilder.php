<?php

declare(strict_types=1);

namespace Szymon\NodeParser\NodeJsonParser;

use Szymon\NodeParser\NodeJsonParser\Node\NodeType;

class JsonToNodesConverterBuilder
{
    public function build(): JsonToNodesConverter
    {
        return new JsonToNodesConverter([
            NodeType::A->value => [
                new NodeAttributeValidator(
                    'href',
                    true,
                    '/^.*$/',
                    ['https://', 'http://'],
                    [],
                ),
            ],
            NodeType::P->value => [],
            NodeType::UL->value => [],
            NodeType::OL->value => [],
            NodeType::LI->value => [],
            NodeType::H1->value => [],
            NodeType::H2->value => [],
            NodeType::Strong->value => [],
            NodeType::Italic->value => [],
            NodeType::Blockquote->value => [],
            NodeType::Pre->value => [],
            NodeType::Sub->value => [],
            NodeType::Sup->value => [],
            NodeType::Strike->value => [],
            NodeType::TextNode->value => [],
        ]);
    }
}
