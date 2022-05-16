<?php

declare(strict_types=1);

namespace Milo\EmbeddedSvg\Latte;

use Latte\CompileException;
use Latte\Compiler\Node;
use Latte\Compiler\Tag;
use Latte\Extension;
use LogicException;
use Milo\EmbeddedSvg\Latte\Node\EmbeddedSvgNode;

final class EmbeddedLatteExtension extends Extension
{
    public function __construct(
        private string $baseDir
    ) {
        $this->validate($baseDir);
    }

    /**
     * @return array<string, \Closure>
     */
    public function getTags(): array
    {
        // add former "macros" here :)
        // @see https://github.com/nette/application/commit/7bfe14fd214c728cec1303b7b486b2f1e4dc4c43#diff-f478cae07da9b043d8410bf46671215af5c8ffb8bdd430beb395ed8b63e52ffcR54
        return [
            'embeddedSvg' => function (Tag $tag): EmbeddedSvgNode {
                return new EmbeddedSvgNode($tag, $this->baseDir);
            },
        ];
    }

    private function validate(string $baseDir): void
    {
        if (! extension_loaded('dom')) {
            throw new LogicException('Missing PHP extension xml');
        }

        if (! is_dir($baseDir)) {
            $errorMessage = sprintf('Base directory for SVG images "%s" does not exist', $baseDir);
            throw new CompileException($errorMessage);
        }
    }
}
