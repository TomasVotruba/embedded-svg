<?php

declare(strict_types=1);

namespace Milo\EmbeddedSvg\XML;

use DOMDocument;
use Milo\EmbeddedSvg\Exception\CompileException;
use Milo\EmbeddedSvg\Exception\XmlErrorException;
use Milo\EmbeddedSvg\MacroSetting;

final class SvgDOMDocumentFactory
{
    public function create(string $svgFilePath, MacroSetting $macroSetting): DOMDocument
    {
        XmlErrorException::try();
        $domDocument = new DOMDocument('1.0', 'UTF-8');
        $domDocument->preserveWhiteSpace = false;

        // @ - triggers warning on empty XML
        @$domDocument->load($svgFilePath, $macroSetting->libXmlOptions);

        $xmlErrorException = XmlErrorException::catch();
        if ($xmlErrorException instanceof XmlErrorException) {
            $errorMessage = sprintf('Failed to load SVG content from "%s"', $svgFilePath);
            throw new CompileException($errorMessage, 0, $xmlErrorException);
        }

        // invoke events
        foreach ($macroSetting->onLoad as $callback) {
            $callback($domDocument, $macroSetting);
        }

        /** @var \DOMElement $documentElement */
        $documentElement = $domDocument->documentElement;

        if (strtolower($documentElement->nodeName) !== 'svg') {
            $errorMessage = sprintf('Only <svg> (non-prefixed) root element is supported but "%s" is used.', $domDocument->documentElement->nodeName);
            throw new CompileException($errorMessage);
        }

        return $domDocument;
    }
}
