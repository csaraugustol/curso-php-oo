<?php

namespace LojaVirtual\Entity;

use LojaVirtual\DataBase\Entity;

class UserOrder extends Entity
{
    protected $table = 'user_orders';

    //Método que passa os dados para inserção
    public function createOrder(array $data = [])
    {
        return $this->insert($data);
    }
}
