<?php

declare(strict_types=1);

namespace Milo\EmbeddedSvg\Configuration;

<<<<<<< HEAD:src/MacroSetting.php
use Milo\EmbeddedSvg\Exception\ConfigurationException;

=======
>>>>>>> prepare for Latte 3, remove unused exception interface:src/Configuration/MacroSetting.php
final class MacroSetting
{
    public string $baseDir;

<<<<<<< HEAD:src/MacroSetting.php
    public int $libXmlOptions;

    public bool $prettyOutput;
=======
    /**
     * @var int
     */
    public $libXmlOptions = LIBXML_NOBLANKS;
>>>>>>> prepare for Latte 3, remove unused exception interface:src/Configuration/MacroSetting.php

    public string $macroName = 'embeddedSvg';

    /**
     * @var array<string, mixed>
     */
    public array $defaultAttributes;

    /**
<<<<<<< HEAD
     * @var callable[]
=======
     * @var array<string, mixed>
>>>>>>> make the string array work
     */
<<<<<<< HEAD:src/MacroSetting.php
    public array $onLoad;

    /**
     * @param array<string, mixed> $settings
     */
    public function __construct(array $settings)
    {
        if (! isset($settings['baseDir'])) {
            throw new ConfigurationException('"baseDir" parameter is missing in embeddable svg configuration');
        }
=======
    public $onLoad = [];

    /**
     * @param array<string, mixed> $settings
     */
    public function __construct(array $settings)
    {
        foreach ($settings as $property => $value) {
            $this->{$property} = $value;
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value): void
    {
        throw new \LogicException('Cannot write to an undeclared property ' . static::class . "::\$${name}.");
    }
>>>>>>> prepare for Latte 3, remove unused exception interface:src/Configuration/MacroSetting.php

        $this->baseDir = $settings['baseDir'];

        $this->macroName = $settings['macroName'] ?? 'embeddedSvg';
        $this->libXmlOptions = $settings['libXmlOptions'] ?? LIBXML_NOBLANKS;
        $this->prettyOutput = $settings['prettyOutput'] ?? false;

        $this->onLoad = $settings['onLoad'] ?? [];
        $this->defaultAttributes = $settings['defaultAttributes'] ?? [];
    }
}
