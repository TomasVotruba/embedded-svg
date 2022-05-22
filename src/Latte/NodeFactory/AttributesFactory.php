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

        if ($domElement->attributes instanceof \DOMNamedNodeMap) {
            foreach ($domElement->attributes as $attribute) {
                $attributes[$attribute->name] = $attribute->value;
            }
        }

        return $attributes;
    }
}
