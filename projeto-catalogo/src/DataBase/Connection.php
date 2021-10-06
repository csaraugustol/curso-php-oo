<?php

namespace Instituicao\DataBase;

use PDO;

class Connection
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new PDO('mysql:dbname=formacao_php;host=localhost', 'indb', '230700');
            self::$instance->exec('SET NAMES UTF8');
        }

        return self::$instance;
    }
}
