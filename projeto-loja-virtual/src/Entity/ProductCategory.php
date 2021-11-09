<?php

namespace LojaVirtual\Entity;

use LojaVirtual\DataBase\Entity;

class ProductCategory extends Entity
{
    /**
     * Nome da tabela
     *
     * @var string
     */
    protected $table = 'products_categories';

    /**
     * Deleta todas as referências existentes e insere as novas passadas
     *
     * @param int $productId
     * @param array $data
     * @return void
     */
    public function syncCategoriesOfProduct(int $productId, array $dataCategories): void
    {
        $this->deleteCategoriesOfProduct(['product_id' => $productId]);

        foreach ($dataCategories as $categoryId) {
            $saveCategorie = [
                'product_id'  => $productId,
                'category_id' => $categoryId
            ];
            $this->insertCategories($saveCategorie);
        }
    }
}
