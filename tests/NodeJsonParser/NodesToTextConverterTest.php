<?php

declare(strict_types=1);

namespace Tests\NodeJsonParser;

use PHPUnit\Framework\TestCase;
use Szymon\NodeParser\NodeJsonParser\Node\Node;
use Szymon\NodeParser\NodeJsonParser\Node\NodeType;
use Szymon\NodeParser\NodeJsonParser\Node\TextNode;
use Szymon\NodeParser\NodeJsonParser\NodesToTextConverter;

class NodesToTextConverterTest extends TestCase
{
    public function testConvertToText(): void
    {
        $converter = new NodesToTextConverter();
        $text = $converter->convertToText([
            new Node(NodeType::H1, [], [
                new TextNode('header'),
            ]),
            new Node(NodeType::P, [], [
                new TextNode('the first '),
                new Node(
                    NodeType::Strong,
                    [],
                    [
                        new TextNode('line '),
                    ],
                ),
                new Node(
                    NodeType::A,
                    [
                        'href' => 'https://google.com',
                    ],
                    [
                        new TextNode('link'),
                    ],
                ),
            ]),
            new TextNode('text'),
        ]);

        $this->assertEquals(
            '
header

the first line link
text',
            $text,
        );
    }
}