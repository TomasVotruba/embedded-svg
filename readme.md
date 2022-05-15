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
latte:
    extensions:
<<<<<<< HEAD
        - Milo\EmbeddedSvg\Latte\EmbeddedLatteExtension(%embeddedSvg%)
```

<<<<<<< HEAD
=======

## Configuration

There are some optional paramers:

```neon
parameters:
    embeddedSvg:
        # change name of the macro
        macroName: svgIcon

        # pretty format SVG content (indent tags)
        prettyOutput: yes

        # default <svg> tag attributes, for example
        defaultAttributes:
            class: embedded
            height: 30px
            width: null

        # callbacks called when SVG loaded from file
        onLoad:
            - [SVGSanitizer, sanitize]

        # bitmask of LIBXML_* flags for DOMDocument::load() method
        libXmlOptions: (integer)
```

You can load the extension more then once. In this case,
change macro name by `macroName` option.

Option `defaultAttributes` is a XML attributes list for generated `<svg>` tag.
These are merged. The precedence is (higher to lower):
- macro tag attributes
- default attributes
- attributes of `<svg>` tag loaded from file

If the attribute value is `null`, it is not rendered. You can unset
attributes from SVG file in that way.

Callback added into `onLoad` event is called when SVG contents is successfully
loaded into DOM. Its signature is:

```php
function (DOMDocument $dom, \Milo\EmbeddedSvg\Configuration\MacroSetting $setting) {
    ...
}
=======
        - Milo\EmbeddedSvg\Latte\EmbeddedLatteExtension(baseDir: %wwwDir%/img)
>>>>>>> remove options parameters, settings, maybes, keep it simple
```

>>>>>>> prepare for Latte 3, remove unused exception interface
## Caveats & Limitations

Because `embeddedSvg` is a macro, it is compiled into PHP only once and then is cached.
So, when you change the macro configuration, probably in NEON, you have to purge Latte cache.

## Resource for Latte 3

* https://forum.nette.org/cs/35141-latte-3-nejvetsi-vyvojovy-skok-v-dejinach-nette?p=2#p220003
* https://github.com/nette/application/commit/7bfe14fd214c728cec1303b7b486b2f1e4dc4c43
