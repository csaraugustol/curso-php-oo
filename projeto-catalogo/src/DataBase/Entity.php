<?php

namespace Instituicao\DataBase;

use PDO;

abstract class Entity
{
    private $connection;

    //Conexão com o banco
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    //Retorna todos os produtos
    public function findAllProducts()
    {
        $findAllProducts = 'SELECT * FROM products';

        $products = $this->connection->query($findAllProducts);

        return $products->fetchAll(PDO::FETCH_ASSOC);
    }

    //Retorna um produto pelo id
    public function findProductById(int $id)
    {
        $findOneProduct = 'SELECT * FROM products WHERE id = :id';

        $product = $this->connection->prepare($findOneProduct);
        $product->bindValue(':id', $id, PDO::PARAM_INT);
        $product->execute();

        return $product->fetch(PDO::FETCH_ASSOC);
    }
}
