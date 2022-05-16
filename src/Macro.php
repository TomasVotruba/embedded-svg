<?php

declare(strict_types=1);

namespace Milo\EmbeddedSvg;

use Latte\CompileException;
use Latte\Compiler;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\PhpWriter;
use Milo\EmbeddedSvg\XML\SvgDOMDocumentFactory;

class Macro extends MacroSet
{
    public function __construct(
        Compiler $compiler,
        private string $baseDir
    ) {
        if (! extension_loaded('dom')) {
            throw new \LogicException('Missing PHP extension xml.');
        } elseif (! is_dir($baseDir)) {
            throw new CompileException("Base directory '{$baseDir}' does not exist.");
        }

        parent::__construct($compiler);
    }

    /**
     * @param array{baseDir: string} $configuration
     */
    public static function install(Compiler $compiler, array $configuration): void
    {
        $baseDir = $configuration['baseDir'];

        $me = new self($compiler, $baseDir);
        $me->addMacro('embeddedSvg', [$me, 'open']);
    }

    public function open(MacroNode $node, PhpWriter $writer): string
    {
        $file = $node->tokenizer->fetchWord();
        if ($file === null) {
            throw new CompileException('Missing SVG file path.');
        }

        $path = $this->baseDir . DIRECTORY_SEPARATOR . trim($file, '\'"');
        if (! is_file($path)) {
            throw new CompileException("SVG file '${path}' does not exist.");
        }

        $svgDOMDocumentFactory = new SvgDOMDocumentFactory();
        $svgDOMDocument = $svgDOMDocumentFactory->create($path);

        $svgAttributes = [
            'xmlns' => $svgDOMDocument->documentElement->namespaceURI,
        ];
        foreach ($svgDOMDocument->documentElement->attributes as $attribute) {
            $svgAttributes[$attribute->name] = $attribute->value;
        }

        $inner = '';
        $svgDOMDocument->formatOutput = false;
        foreach ($svgDOMDocument->documentElement->childNodes as $childNode) {
            $inner .= $svgDOMDocument->saveXML($childNode);
        }

        return $writer->write(
            '
			echo "<svg";
			foreach (%0.var as $key => $value) {
				if ($value === null || $value === false) {
					continue;
				} elseif ($value === true) {
					echo " " . %escape($key);
				} else {
					echo " " . %escape($key) . "=\"" . %escape($value) . "\"";
				}
			};
			echo ">" . %1.var . "</svg>";
			',
            $svgAttributes,
            $inner
        );
    }
}
