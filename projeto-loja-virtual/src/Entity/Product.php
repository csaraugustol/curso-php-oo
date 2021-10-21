<?php

namespace LojaVirtual\Entity;

use PDO;
use LojaVirtual\DataBase\Entity;

class Product extends Entity
{
    protected $table = 'products';

    public static $filters = [
        'name'        => FILTER_SANITIZE_STRING,
        'description' => FILTER_SANITIZE_STRING,
        'price'       => ['filter' => FILTER_SANITIZE_NUMBER_FLOAT, 'flags' => FILTER_FLAG_ALLOW_THOUSAND],
        'is_active'   => FILTER_SANITIZE_STRING,
        'content'     => FILTER_SANITIZE_STRING,
    ];

    //Método para fazer a busca das imagens relacionadas ao produto
    public function returnProductWithImages($product, $isSlug = false)
    {
        $sqlQuery = 'select
        p.*, pi.id as image_id, pi.image
        from
        products p
        left join
        products_images pi on pi.product_id = p.id';

        if ($isSlug) {
            $sqlQuery .= " where p.slug = :product";
        } else {
            $sqlQuery .= " where p.id = :product";
        }

        $select = $this->connection->prepare($sqlQuery);
        $select->bindValue(':product', $product, $isSlug ? PDO::PARAM_STR : PDO::PARAM_INT);
        $select->execute();

        $productData = [];
        foreach ($select->fetchAll(PDO::FETCH_ASSOC) as $product) {
            $productData['id']          = $product['id'];
            $productData['name']        = $product['name'];
            $productData['description'] = $product['description'];
            $productData['content']     = $product['content'];
            $productData['price']       = $product['price'];
            $productData['is_active']   = $product['is_active'];
            $productData['slug']        = $product['slug'];
            $productData['images'][]    = ['id' => $product['image_id'], 'image' => $product['image']];
        }

        return $productData;
    }

    //Método para retornar a primeira imagem do produto para exibição no site
    public function returnAllProductsWithThumb()
    {
        $sqlQuery = '
            SELECT products.*,
            (SELECT image FROM products_images WHERE product_id = products.id LIMIT 1) AS image FROM products
        ';

        $product = $this->connection->query($sqlQuery);

        return $product->fetchAll(PDO::FETCH_ASSOC);
    }
}
