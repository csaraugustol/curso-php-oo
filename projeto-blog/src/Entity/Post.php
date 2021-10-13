<?php

namespace Blog\Entity;

use Blog\DataBase\Entity;

class Post extends Entity
{
    protected $table = 'posts';

    public static $filters = [
        'title'       => FILTER_SANITIZE_STRING,
        'description' => FILTER_SANITIZE_STRING,
        'content'     => FILTER_SANITIZE_STRING,
        'type'        => FILTER_SANITIZE_STRING,
        'user_id'     => FILTER_SANITIZE_NUMBER_INT,
        'category_id' => FILTER_SANITIZE_NUMBER_INT
    ];
}
