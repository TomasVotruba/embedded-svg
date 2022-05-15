<?php

declare(strict_types=1);

namespace Milo\EmbeddedSvg;

class XmlErrorException extends \ErrorException implements Exception
{
    /**
     * @var bool[]
     */
    private static array $handling = [];

    final public function __construct(\LibXMLError $error, self $previous = null)
    {
        parent::__construct(trim($error->message), $error->code, $error->level, $error->file, $error->line, $previous);
    }

    public static function try(): void
    {
        self::$handling[] = libxml_use_internal_errors(true);
    }

    public static function catch(): ?self
    {
        $e = null;
        foreach (array_reverse(libxml_get_errors()) as $error) {
            $e = new self($error, $e);
        }
        libxml_clear_errors();
        libxml_use_internal_errors(array_pop(self::$handling));
        return $e;
    }
}
