<?php

declare(strict_types=1);

namespace Milo\EmbeddedSvg\Latte\NodeFactory;

use DOMElement;

final class AttributesFactory
{
    /**
     * @return array<string|mixed, mixed>
     */
    public function create(DOMElement $domElement): array
    {
        $attributes = [
            'xmlns' => $domElement->namespaceURI,
        ];
        $xmlAttributeNodes = $this->createFromDomAttributes($domElement);

        return array_merge($attributes, $xmlAttributeNodes);
    }

    /**
     * @return mixed[]
     */
    private function createFromDomAttributes(DOMElement $domElement): array
    {
        $attributes = [];

        foreach ($domElement->attributes as $attribute) {
            $attributes[$attribute->name] = $attribute->value;
        }

        return $attributes;
    }
}
