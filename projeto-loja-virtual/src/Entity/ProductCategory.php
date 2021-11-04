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
     * Verifica se será necessaŕio utilizar
     * o created_at / updated_at
     *
     * @var bool
     */
    protected $timestamps = false;

    /**
     * Deleta todas as referências existentes e insere as novas passadas
     *
     * @param integer $productId
     * @param array $data
     * @return void
     */
    public function sync(int $productId, array $data): void
    {
        $this->delete($productId);

        foreach ($data as $dataInsert) {
            $saveManyToMany = [
                'product_id'  => $productId,
                'category_id' => $dataInsert
            ];
            $this->insert($saveManyToMany);
        }
    }
}
