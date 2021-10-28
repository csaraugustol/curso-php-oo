<?php

namespace Blog\Security\Validator;

class Sanitizer
{
    /**
     * Verifica tipagem dos camṕos vindo do formulário
     *
     * @param array $data
     * @param array $sanitizerFilters
     * @return array
     */
    public static function sanitizeData(array $data, array $sanitizerFilters)
    {
        return filter_var_array($data, $sanitizerFilters);
    }
}