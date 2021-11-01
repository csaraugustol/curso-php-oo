<?php

namespace LojaVirtual\Security\Validator;

class Sanitizer
{
    /**
     * Verifica tipagem dos campos vindo do formulário
     *
     * @param array $data
     * @param array $sanitizerFilters
     * @return array
     */
    public static function sanitizeData(array $data, array $sanitizerFilters): array
    {
        return filter_var_array($data, $sanitizerFilters);
    }
}
