<?php

declare(strict_types=1);

namespace Milo\EmbeddedSvg;

use Milo\EmbeddedSvg\Exception\ConfigurationException;

final class MacroSetting
{
    public string $baseDir;

    public int $libXmlOptions;

    public bool $prettyOutput;

    public string $macroName = 'svg';

    /**
     * @var array<string, mixed>
     */
    public array $defaultAttributes;

    /**
     * @var callable[]
     */
    public array $onLoad;

    /**
     * @param array<string, mixed> $settings
     */
    public function __construct(array $settings)
    {
        if (! isset($settings['baseDir'])) {
            throw new ConfigurationException('"baseDir" parameter is missing in embeddable svg configuration');
        }

        $this->baseDir = $settings['baseDir'];

        $this->macroName = $settings['macroName'] ?? 'svg';
        $this->libXmlOptions = $settings['libXmlOptions'] ?? LIBXML_NOBLANKS;
        $this->prettyOutput = $settings['prettyOutput'] ?? false;

        $this->onLoad = $settings['onLoad'] ?? [];
        $this->defaultAttributes = $settings['defaultAttributes'] ?? [];
    }
}
