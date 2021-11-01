<?php

namespace LojaVirtual\Security\Validator;

class Validator
{
    /**
     * Verifica se há um campo vazio
     *
     * @param array $data
     * @return boolean
     */
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

    /**
     * Verifica igualdade de senha
     *
     * @param string $password
     * @param string $passwordConfirm
     * @return boolean
     */
    public static function validatePasswordConfirm(
        string $password,
        string $passwordConfirm
    ): bool {
        return $password === $passwordConfirm;
    }

    /**
     * Verifica o tamanho mínimo da senha
     *
     * @param string $password
     * @return boolean
     */
    public static function validatePasswordMinStringLenght(string $password): bool
    {
        return strlen($password) >= 6;
    }

    /**
     * Verifica o tipo da extensão da imagem
     *
     * @param array $files
     * @return boolean
     */
    public static function validateImagesFileType(array $files = []): bool
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
