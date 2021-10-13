<?php

namespace Blog\Security\Validator;

class Sanitizer
{
    //Verifica tipagem dos camṕos vindo do formulário
    public static function sanitizeData($data, $sanitizerFilters)
    {
        return filter_var_array($data, $sanitizerFilters);
    }
}
