<?php

use Latte\Runtime as LR;

/** source: {$value} */
final class Template7f33c321c6 extends Latte\Runtime\Template
{

    public function main(): array
    {
        extract($this->params);
        echo LR\Filters::escapeHtmlText($value) /* line 1 */;
        return get_defined_vars();
    }

}
