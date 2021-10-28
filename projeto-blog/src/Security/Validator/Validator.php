<?php

namespace Blog\Security\Validator;

class Validator
{
    /**
     * Verifica se há campo vazio nos dados vindo do formulário
     *
     * @param array $data
     * @return bool
     */
    public static function validateRequiredFields(array $data): bool
    {
        foreach ($data as $param => $value) {
            if (is_null($data[$param])) {
                return false;
                break;
            }
        }

        return true;
    }

    /**
     * Verifica se senha e sua confirmação são iguais
     *
     * @param string $password
     * @param string $passwordConfirm
     * @return bool
     */
    public static function validatePasswordConfirm(
        string $password,
        string $passwordConfirm
    ): bool {
        return $password === $passwordConfirm;
    }

    /**
     * Verifica se a senha atende o tamanho minímo desejado
     *
     * @param string $password
     * @return bool
     */
    public static function validatePasswordMinStringLenght(string $password): bool
    {
        return strlen($password) >= 6;
    }
}
