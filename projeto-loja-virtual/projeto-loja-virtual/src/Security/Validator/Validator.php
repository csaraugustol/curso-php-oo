<?php

namespace LojaVirtual\Security\Validator;

class Validator
{
    //Verifica se há um campo vazio
    public static function validateRequiredFields(array $data): bool
    {
        foreach ($data as $param => $value) {
            if (!$data[$param]) {
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

    //Verifica o tipo do arquivo da imagem
    public static function validateImagesFileType($files = []): bool
    {
        $isValideImages = true;
        $allowedImagesFile = ['image/jpeg', 'image/png', 'image/jpg'];

        for ($i = 0; $i < count($files['type']); $i++) {
            if (!in_array($files['type'][$i], $allowedImagesFile)) {
                $isValideImages = false;
            }
        }

        return $isValideImages;
    }
}
