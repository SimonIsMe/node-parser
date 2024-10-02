<?php

declare(strict_types=1);

namespace Tests\NodeJsonParser;

use PHPUnit\Framework\TestCase;
use Szymon\NodeParser\NodeJsonParser\NodeAttributeValidator;

class NodeAttributeValidatorTest extends TestCase
{
    /**
     * @dataProvider dataProviderForTestIsValid
     */
    public function testIsValid(bool $isValid, string $text): void
    {
        $validator = new NodeAttributeValidator(
            'name',
            true,
            '/^.*$/',
            ['prefix1', 'prefix2'],
            ['suffix1', 'suffix2'],
        );

        $this->assertEquals(
            $isValid,
            $validator->isValid($text),
        );
    }

    public function dataProviderForTestIsValid(): array
    {
        return [
            [ true, 'prefix1suffix1' ],
            [ true, 'prefix1suffix2' ],
            [ true, 'prefix2suffix1' ],
            [ true, 'prefix2suffix2' ],

            [ false, 'prefisuffix1' ],
            [ false, 'prefix1ffix2' ],
            [ false, 'prefix2ffix1' ],
            [ false, 'prsuffix2' ],
        ];
    }

    /**
     * @dataProvider dataProviderForTestIsValidWithRegularExpression
     */
    public function testIsValidWithRegularExpression(bool $isValid, string $text): void
    {
        $validator = new NodeAttributeValidator(
            'name',
            true,
            '/^(p1|p2)[0-9]{1,3}(s1|s2)$/',
            ['p1', 'p2'],
            ['s1', 's2'],
        );

        $this->assertEquals(
            $isValid,
            $validator->isValid($text),
        );
    }

    public function dataProviderForTestIsValidWithRegularExpression(): array
    {
        return [
            [ true, 'p11s2' ],
            [ true, 'p112s2' ],
            [ true, 'p1123s2' ],
            [ true, 'p2123s1' ],
            [ false, 'p21234s1' ],
            [ false, 'p2s1' ],
        ];
    }
}
