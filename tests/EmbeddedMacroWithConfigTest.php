<?php

declare(strict_types=1);

namespace Milo\EmbeddedSvg\Tests;

final class EmbeddedMacroWithConfigTest extends AbstractEmbeddedMacroTest
{
    public function provideConfig(): string
    {
        return __DIR__ . '/config/config_with_params.neon';
    }

    public function provideFixtureDirectory(): string
    {
        return __DIR__ . '/FixtureWithParams';
    }
}
