<?php

namespace LojaVirtual\Security;

class PasswordHash
{
    //Salva senha criptografada no banco
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_ARGON2I);
    }

    //Verifica senha criptografada se são similares
    public static function checkPassword($password, $passwordHashed)
    {
        return password_verify($password, $passwordHashed);
    }
}
