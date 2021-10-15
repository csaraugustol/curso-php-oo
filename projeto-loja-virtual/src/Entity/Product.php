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

    public function returnProductWithImages($productId)
    {
         $sql = 'select
        p.*, pi.id as image_id, pi.image
        from
        products p
        inner join
        products_images pi on pi.product_id = p.id
        where p.id = :productId';

       // $sql = 'select p.*, pi.id as image_id, pi.image from products inner join products_images pi on pi.product_id = p.id where p.id = :productId';

        $select = $this->connection->prepare($sql);
        $select->bindValue(':productId', $productId, PDO::PARAM_INT);
        $select->execute();

        $productData = [];
        foreach ($select->fetchAll(PDO::FETCH_ASSOC) as $product) {
            $productData['id'] = $product['id'];
            $productData['name'] = $product['name'];
            $productData['description'] = $product['description'];
            $productData['content'] = $product['content'];
            $productData['price'] = $product['price'];
            $productData['is_active'] = $product['is_active'];
            $productData['images'][] = ['id' => $product['image_id'], 'image' => $product['image']];
        }

        return $productData;
    }
}
