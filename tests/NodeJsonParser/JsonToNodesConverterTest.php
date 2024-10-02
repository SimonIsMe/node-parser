<?php

declare(strict_types=1);

namespace Tests\NodeJsonParser;

use PHPUnit\Framework\TestCase;
use Szymon\NodeParser\NodeJsonParser\InvalidNodeJsonValueException;
use Szymon\NodeParser\NodeJsonParser\JsonToNodesConverterBuilder;
use Szymon\NodeParser\NodeJsonParser\Node\NodeType;

class JsonToNodesConverterTest extends TestCase
{
    public function testConvert(): void
    {
        $converter = (new JsonToNodesConverterBuilder())->build();
        $nodes = $converter->convert(
            '[{"type":"a","attributes":{"href":"https://google.com"},"children":[ {"type":"text", '
            . '"value":"https://google.com" } ] }]',
        );

        $this->assertEquals(
            NodeType::A,
            $nodes[0]->getType(),
        );
        $this->assertEquals(
            [ 'href' => 'https://google.com' ],
            $nodes[0]->getAttributes(),
        );
        $this->assertEquals(
            'https://google.com',
            $nodes[0]->getChildren()[0]->getValue(),
        );
    }

    /**
     * @dataProvider dataProviderForTestConvertWithInvalidJson
     */
    public function testConvertWithInvalidJson(string $json): void
    {
        $converter = (new JsonToNodesConverterBuilder())->build();

        $this->expectException(InvalidNodeJsonValueException::class);
        $converter->convert($json);
    }

    public function dataProviderForTestConvertWithInvalidJson(): array
    {
        return [
            [ '[{"type":"a","attributes":{"href":"https://google.com"}}]' ],
            [ '[{"type":"a","children":[ {"type":"text", "value":"https://google.com" } ] }]' ],
            [
                '[{"type":"a","attributes":{"href":"invalid"},"children":[ {"type":"text", '
                . '"value":"https://google.com" } ] }]',
            ],
            [ '[{"type":"a","attributes":{},"children":[ {"type":"text", "value":"https://google.com" } ] }]' ],
            [ 'invalid' ],
        ];
    }
}
