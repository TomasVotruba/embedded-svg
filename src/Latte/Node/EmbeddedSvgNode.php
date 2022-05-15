<?php

declare(strict_types=1);

namespace Milo\EmbeddedSvg\Latte\Node;

use DOMDocument;
use DOMElement;
use Latte\Compiler\Nodes\Html\ElementNode;
use Latte\Compiler\Nodes\Php\Scalar\StringNode;
use Latte\Compiler\Nodes\TextNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Milo\EmbeddedSvg\Exception\CompileException;
use Milo\EmbeddedSvg\Latte\NodeFactory\AttributesFactory;
use Milo\EmbeddedSvg\XML\SvgDOMDocumentFactory;

/**
 * @see https://github.com/nette/application/commit/7bfe14fd214c728cec1303b7b486b2f1e4dc4c43#diff-4962238ef3db33964744f40410cbdbc9d50b4b1620725ddf6ff34701c64bc51fR25
 *
 * {embeddedSvg "circle.svg"}
 * ↓
 * <svg width="x" height="y"><circle cx="4.5" cy="4.5" r="3.5"/></svg>
 */
final class EmbeddedSvgNode extends ElementNode
{
    /**
     * @var string
     */
    public const ELEMENT_NAME = 'svg';

    private string $svgFilepath;

    private AttributesFactory $attributesFactory;

    private SvgDOMDocumentFactory $svgDOMDocumentFactory;

    public function __construct(
        Tag $tag,
        string $baseDir
    ) {
        // services
        $this->attributesFactory = new AttributesFactory();
        $this->svgDOMDocumentFactory = new SvgDOMDocumentFactory();

        parent::__construct(self::ELEMENT_NAME);

        // node requires at least 1 argument, the filename
        $this->svgFilepath = $this->resolveCompleteFilePath($tag, $baseDir);
    }

    public function print(PrintContext $context): string
    {
        $domDocument = $this->svgDOMDocumentFactory->create($this->svgFilepath);

        /** @var DOMElement $documentElement */
        $documentElement = $domDocument->documentElement;

        $attributes = $this->attributesFactory->create($documentElement);

        $domDocument->formatOutput = false;

        $innerSvgContent = $this->createInnerSvgContent($documentElement, $domDocument);

        return $context->format(
            <<<'MACRO_CONTENT'
echo '<svg';

foreach (%dump as $key => $value) {
    if ($value === null || $value === false) {
        continue;
    } elseif ($value === true) {
        echo ' ' . %escape($key);
    } else {
        echo ' ' . %escape($key) . "='" . %escape($value) . "'";
    }
}

%raw
echo '</svg>';
MACRO_CONTENT,
            $attributes,
            new TextNode($innerSvgContent)
        );
    }

    private function resolveCompleteFilePath(Tag $tag, string $baseDir): string
    {
        $filename = $tag->parser->parseUnquotedStringOrExpression();

        if (! $filename instanceof StringNode) {
            throw new CompileException('Missing SVG file path.');
        }

        $absoluteFilename = $baseDir . DIRECTORY_SEPARATOR . $filename->value;

        if (! is_file($absoluteFilename)) {
            $errorMessage = sprintf('SVG file "%s" does not exist.', $absoluteFilename);
            throw new CompileException($errorMessage);
        }

        return $absoluteFilename;
    }

    private function createInnerSvgContent(DOMElement $documentElement, DOMDocument $domDocument): string
    {
        $content = '';
        foreach ($documentElement->childNodes as $childNode) {
            $content .= $domDocument->saveXML($childNode);
        }

        return $content;
    }
}
