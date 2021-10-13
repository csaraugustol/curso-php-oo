<?php

namespace Blog\Security\Validator;

class Validator
{
    //Verifica se há um campo vazio
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

    //Verifica igual de senha
    public static function validatePasswordConfirm($password, $passwordConfirm): bool
    {
        return $password === $passwordConfirm;
    }

    //Verifica o tamanho minímo da senha
    public static function validatePasswordMinStringLenght($password): bool
    {
        return strlen($password) >= 6;
    }
}
