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
    private MacroSetting $setting;

    public function __construct(Compiler $compiler, MacroSetting $setting)
    {
        if (! extension_loaded('dom')) {
            throw new \LogicException('Missing PHP extension xml.');
        } elseif (! is_dir($setting->baseDir)) {
            throw new CompileException("Base directory '{$setting->baseDir}' does not exist.");
        }

        parent::__construct($compiler);
        $this->setting = $setting;
    }

    public static function install(Compiler $compiler, MacroSetting $setting): void
    {
        $me = new self($compiler, $setting);
        $me->addMacro($setting->macroName, [$me, 'open']);
    }

    public function open(MacroNode $node, PhpWriter $writer): string
    {
        $file = $node->tokenizer->fetchWord();
        if ($file === null) {
            throw new CompileException('Missing SVG file path.');
        }

        $path = $this->setting->baseDir . DIRECTORY_SEPARATOR . trim($file, '\'"');
        if (! is_file($path)) {
            throw new CompileException("SVG file '${path}' does not exist.");
        }

        $svgDOMDocumentFactory = new SvgDOMDocumentFactory();
        $svgDOMDocument = $svgDOMDocumentFactory->create($path, $this->setting);

        $macroAttributes = $writer->formatArray();
        $svgAttributes = [
            'xmlns' => $svgDOMDocument->documentElement->namespaceURI,
        ];
        foreach ($svgDOMDocument->documentElement->attributes as $attribute) {
            $svgAttributes[$attribute->name] = $attribute->value;
        }

        $inner = '';
        $svgDOMDocument->formatOutput = $this->setting->prettyOutput;
        foreach ($svgDOMDocument->documentElement->childNodes as $childNode) {
            $inner .= $svgDOMDocument->saveXML($childNode);
        }

        return $writer->write(
            '
			echo "<svg";
			foreach (%0.raw + %1.var as $n => $v) {
				if ($v === null || $v === false) {
					continue;
				} elseif ($v === true) {
					echo " " . %escape($n);
				} else {
					echo " " . %escape($n) . "=\"" . %escape($v) . "\"";
				}
			};
			echo ">" . %2.var . "</svg>";
			',
            $macroAttributes,
            $this->setting->defaultAttributes + $svgAttributes,
            $inner
        );
    }
}
