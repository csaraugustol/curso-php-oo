<?php

namespace Blog\Entity;

use Blog\DataBase\Entity;

class Category extends Entity
{
    /**
     * Nome tabela
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * Filtragem Sanitazer
     *
     * @var array
     */
    public static $filters = [
        'name'        => FILTER_SANITIZE_STRING,
        'description' => FILTER_SANITIZE_STRING,
    ];
}
