<?php

namespace GGP\DataBase;

use PDO;

class Connection
{
    /**
     * Undocumented variable
     *
     * @var PDO
     */
    private static $instance = null;

    /**
     * Retorna conexão com o banco de dados
     *
     * @return PDO
     */
    public static function getInstance(): PDO
    {
        if (is_null(self::$instance)) {
            self::$instance = new PDO('mysql:dbname=my_expenses;host=localhost', 'indb', '230700');
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->exec('SET NAMES UTF8');
        }

        return self::$instance;
    }
}
