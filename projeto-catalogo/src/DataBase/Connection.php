<?php

namespace Catalogo\DataBase;

use PDO;

class Connection
{
    /**
     * Instância de conexão
     *
     * @var PDO
     */
    private static $instance = null;

    /**
     * Retorna string de conexão com o banco
     *
     * @return PDO
     */
    public static function getInstance(): PDO
    {
        if (is_null(self::$instance)) {
            self::$instance = new PDO('mysql:dbname=;host=', '', '');
            self::$instance->exec('SET NAMES UTF8');
        }

        return self::$instance;
    }
}
