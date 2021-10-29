<?php

namespace Catalogo\DataBase;

use PDO;

abstract class Entity
{
    /**
     * Conexão com o banco
     *
     * @var PDO
     */
    private $connection;

    /**
     * Recebe string de conexão por parâmetro
     *
     * @param PDO $connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Retorna todos os produtos
     *
     * @return array
     */
    public function findAllProducts(): array
    {
        $findAllProducts = 'SELECT * FROM products';
        $products = $this->connection->query($findAllProducts);
        return $products->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retorna um produto pelo id
     *
     * @param int $id
     * @return array
     */
    public function findProductById(int $id): array
    {
        $findOneProduct = 'SELECT * FROM products WHERE id = :id';
        $product = $this->connection->prepare($findOneProduct);
        $product->bindValue(':id', $id, PDO::PARAM_INT);
        $product->execute();
        return $product->fetch(PDO::FETCH_ASSOC);
    }
}
