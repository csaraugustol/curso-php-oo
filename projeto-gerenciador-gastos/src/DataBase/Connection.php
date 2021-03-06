<?php

namespace GGP\DataBase;

use PDO;

class Connection
{
    /**
     * Cria uma instância da conexão com o banco
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
            self::$instance = new PDO('mysql:dbname=;host=', '', '');
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->exec('SET NAMES UTF8');
        }

        return self::$instance;
    }
}
