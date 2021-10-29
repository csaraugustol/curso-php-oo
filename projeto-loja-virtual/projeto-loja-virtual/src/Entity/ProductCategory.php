<?php

namespace LojaVirtual\Entity;

use LojaVirtual\DataBase\Entity;

class ProductCategory extends Entity
{
    protected $table = 'products_categories';
    protected $timestamps = false;

    //Deleta todas as referências existentes e insere as novas passadas
    public function sync(int $productId, array $data)
    {
        $this->delete(['product_id' => $productId]);

        foreach ($data as $dataInsert) {
            $saveManyToMany = [
                'product_id'  => $productId,
                'category_id' => $dataInsert
            ];
            $this->insert($saveManyToMany);
        }
    }
}
