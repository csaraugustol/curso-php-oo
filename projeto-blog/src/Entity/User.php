<?php

namespace Blog\Entity;

use Blog\DataBase\Entity;

class User extends Entity
{
    /**
     * Nome tabela
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Filtragem Sanitazer
     *
     * @var array
     */
    public static $filters = [
        'first_name'       => FILTER_SANITIZE_STRING,
        'last_name'        => FILTER_SANITIZE_STRING,
        'username'         => FILTER_SANITIZE_STRING,
        'email'            => FILTER_SANITIZE_STRING,
        'password'         => FILTER_SANITIZE_STRING,
        'password_confirm' => FILTER_SANITIZE_STRING,
    ];
}
