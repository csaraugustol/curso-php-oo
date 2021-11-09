<?php

namespace LojaVirtual\Security;

class PasswordHash
{
    /**
     * Salva senha criptografada no bancon
     *
     * @param string $password
     * @return string
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_ARGON2I);
    }

    /**
     * Verifica senha criptografada se são similares
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
