<?php

namespace LojaVirtual\DataBase;

use PDO;

class Connection
{
    private static $instance = null;

    private function __construct()
    {
    }

    //Método de conexão com o banco de dados
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new PDO('mysql:dbname=' . DB_NAME . ';host=' . DB_HOST, DB_USER, DB_PASSWORD);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->exec('SET NAMES ' . DB_CHARSET);
        }
        return self::$instance;
    }
}
