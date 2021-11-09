<?php

namespace LojaVirtual\Entity;

use LojaVirtual\DataBase\Entity;

class User extends Entity
{
    /**
     * Nome da tabela
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Filtragem dos dados recebidos
     *
     * @var array
     */
    public static $filters = [
        'first_name'       => FILTER_SANITIZE_STRING,
        'last_name'        => FILTER_SANITIZE_STRING,
        'email'            => FILTER_SANITIZE_EMAIL,
        'password'         => FILTER_SANITIZE_STRING,
        'password_confirm' => FILTER_SANITIZE_STRING,
    ];
}
