<?php

declare(strict_types=1);

namespace Szymon\NodeParser\NodeJsonParser;

use Szymon\NodeParser\NodeJsonParser\Node\Node;
use Szymon\NodeParser\NodeJsonParser\Node\NodeType;
use Szymon\NodeParser\NodeJsonParser\Node\TextNode;

class JsonToNodesConverter
{
    /**
     * @param NodeAttributeValidator[NodeType][] $nodeAttributeValidators
     */
    public function __construct(
        private readonly array $nodeAttributeValidators = [],
    ) {
    }

    /**
     * @return Node[] $nodes
     * @throws InvalidNodeJsonValueException
     */
    public function convert(string $json): array
    {
        $json = json_decode($json, true);

        if ($json === null) {
            throw new InvalidNodeJsonValueException('Invalid JSON');
        }

        return $this->convertToListOfNodes($json);
    }

    /**
     * @return Node[]
     * @throws InvalidNodeJsonValueException
     */
    private function convertToListOfNodes(array $array): array
    {
        return array_map(
            fn (array $nodeArray) => $this->convertToNode($nodeArray),
            $array,
        );
    }

    /**
     * @param array $nodeArray
     * @return Node
     * @throws InvalidNodeJsonValueException
     */
    private function convertToNode(array $nodeArray): Node
    {
        $nodeType = NodeType::from($nodeArray['type']);
        $this->checkIfTagCanBeUsed($nodeType);

        if ($nodeArray['type'] === NodeType::TextNode->value) {
            return $this->convertToTextNode($nodeArray);
        }

        $requiredKeys = ['type', 'attributes', 'children'];
        if (empty(array_diff($requiredKeys, array_keys($nodeArray))) === false) {
            throw new InvalidNodeJsonValueException('Invalid JSON');
        }
        $attributes = $nodeArray['attributes'];

        $this->checkIfAllProvidedAttributesAreWhitelisted($nodeType, $attributes);
        $this->checkIfAttributesAreValid($nodeType, $attributes);
        $this->checkIfAllRequiredAttributesAreProvided($nodeType, $attributes);

        return new Node(
            $nodeType,
            $attributes,
            $this->convertToListOfNodes($nodeArray['children']),
        );
    }

    private function convertToTextNode(array $nodeArray): TextNode
    {
        return new TextNode(
            $nodeArray['value'],
        );
    }

    private function checkIfTagCanBeUsed(NodeType $nodeType): void
    {
        if (array_key_exists($nodeType->value, $this->nodeAttributeValidators) === false) {
            throw new InvalidNodeJsonValueException('This type are not allowed: ' . $nodeType->value);
        }
    }

    private function checkIfAllProvidedAttributesAreWhitelisted(NodeType $nodeType, array $attributes): void
    {
        $acceptedAttributes = array_map(
            fn (NodeAttributeValidator $nodeAttributeValidator) => $nodeAttributeValidator->getAttributeName(),
            $this->nodeAttributeValidators[$nodeType->value],
        );
        $diff = array_diff(array_keys($attributes), $acceptedAttributes);

        if (empty($diff) === false) {
            throw new InvalidNodeJsonValueException('This attributes are not allowed: ' . implode(', ', $diff));
        }
    }

    private function checkIfAttributesAreValid(NodeType $nodeType, array $attributes): void
    {
        foreach ($attributes as $attributeName => $attributeValue) {
            foreach ($this->nodeAttributeValidators[$nodeType->value] as $attributeValidator) {
                if ($attributeValidator->getAttributeName() !== $attributeName) {
                    continue;
                }

                if ($attributeValidator->isValid($attributeValue) === false) {
                    throw new InvalidNodeJsonValueException(
                        $nodeType->value . ' attribute has invalid value: "' . $attributeValue . '"',
                    );
                }
            }
        }
    }

    private function checkIfAllRequiredAttributesAreProvided(NodeType $nodeType, array $attributes): void
    {
        if (array_key_exists($nodeType->value, $this->nodeAttributeValidators)) {
            foreach ($this->nodeAttributeValidators[$nodeType->value] as $attributeValidator) {
                if ($attributeValidator->isRequired() === false) {
                    continue;
                }

                if (array_key_exists($attributeValidator->getAttributeName(), $attributes) === false) {
                    throw new InvalidNodeJsonValueException(
                        'The "' . $attributeValidator->getAttributeName()
                        . '" attribute is required, but it is not provided',
                    );
                }
            }
        }
    }
}
