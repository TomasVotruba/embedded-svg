<?php

declare(strict_types=1);

namespace Milo\EmbeddedSvg\Tests;

final class EmbeddedMacroTest extends AbstractEmbeddedMacroTest
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
