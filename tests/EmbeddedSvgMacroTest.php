<?php

declare(strict_types=1);

namespace Milo\EmbeddedSvg\Tests;

final class EmbeddedSvgMacroTest extends AbstractMacroTest
{
    public function provideConfig(): string
    {
        return __DIR__ . '/config/test_config.neon';
    }

    public function provideFixtureDirectory(): string
    {
        return __DIR__ . '/Fixture';
    }
}
