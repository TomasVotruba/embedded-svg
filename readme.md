# Embedded Macro for Latte

## Example

```latte
<h1>
    Publications
    {embeddedSvg 'icons/help.svg'}
</h1>
```

Result HTML code will look like:

```html
<h1>
    Publications
    <svg xmlns="..." >
        ... content of help.svg file ...
    </svg>
</h1>
```

## Purpose

This is a single purpose helper library with a macro definition for [Latte](https://latte.nette.org/), the PHP templating engine.
It loads SVG source file and embed it into HTML code in compile time.

This allows to stylize SVG by CSS. It isn't easy with SVG linked as an image `<img src="icons/help.svg">`.

## Installation

Require library:

```bash
composer require milo/embedded-svg
```

Register extension in your `config.neon` and configure it:

```neon
extensions:
    embeddedSvg: Milo\EmbeddedSvg\Extension

embeddedSvg:
    baseDir: %wwwDir%/img
```

## Caveats & Limitations

Because `embeddedSvg` is a macro, it is compiled into PHP only once and then is cached.
So, when you change the macro configuration, probably in NEON, you have to purge Latte cache.

## Resource for Latte 3

* https://forum.nette.org/cs/35141-latte-3-nejvetsi-vyvojovy-skok-v-dejinach-nette?p=2#p220003
* https://github.com/nette/application/commit/7bfe14fd214c728cec1303b7b486b2f1e4dc4c43
