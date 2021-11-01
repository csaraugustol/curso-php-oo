<?php

namespace LojaVirtual\Entity;

use LojaVirtual\DataBase\Entity;

class UserOrder extends Entity
{
    /**
     * Nome da tabela
     *
     * @var string
     */
    protected $table = 'user_orders';

    /**
     * Retorna array de pedidos
     *
     * @param array $data
     * @return array
     */
    public function createOrder(array $data = []): array
    {
        return $this->insert($data);
    }
}
