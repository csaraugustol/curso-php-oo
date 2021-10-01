<?php

class Html
{
    // Estado temporário
    public static $mainTag = '<html>';

    // Estado fixo e constante sempre com letra maiúscula
    const END_TAG = '</html>';

    public static function openTagHtml()
    {
        // Para acessar um atributo estático usa o "self" ao invés do "$this"
        return self::$mainTag;
    }

    public static function endTagHtml()
    {
        return self::END_TAG;
    }
}

// $html = new Html();
// print $html->openTagHtml();

// Acesso a um método/atributo estático. Possível acesar sem instanciar a classe
print Html::openTagHtml();
print "\n";
print Html::endTagHtml();
print "\n";
print Html::$mainTag;
print "\n";
print Html::END_TAG;
