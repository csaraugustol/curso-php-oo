<?php

namespace Blog\Security;

class PasswordHash
{
    /**
     * Realiza criptografia da senha para armazenar no banco
     *
     * @param string $password
     * @return string
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_ARGON2I);
    }

    /**
     * Verifica se senhas são iguais para efetuar o login
     *
     * @param string $password
     * @param string $passwordHashed
     * @return string
     */
    public static function checkPassword(
        string $password,
        string $passwordHashed
    ): string {
        return password_verify($password, $passwordHashed);
    }
}
