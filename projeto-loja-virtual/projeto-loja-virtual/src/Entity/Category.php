<?php

namespace LojaVirtual\Entity;

use LojaVirtual\DataBase\Entity;

class Category extends Entity
{
    protected $table = 'categories';

    public static $filters = [
        'name'        => FILTER_SANITIZE_STRING,
        'description' => FILTER_SANITIZE_STRING
    ];
}