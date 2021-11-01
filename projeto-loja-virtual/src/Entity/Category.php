<?php

namespace LojaVirtual\Entity;

use LojaVirtual\DataBase\Entity;

class Category extends Entity
{
    /**
     * Nome da tabela
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * Filtragem dos dados recebidos
     *
     * @var array
     */
    public static $filters = [
        'name'        => FILTER_SANITIZE_STRING,
        'description' => FILTER_SANITIZE_STRING,
    ];
}
