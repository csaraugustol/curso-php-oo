<?php

namespace agregation;

class Product
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $price;

    /**
     * Retorna id do produto
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Recebe id do produto
     *
     * @param int $id
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Retorna nome do produto
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Recebe nome do produto
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Retorna preço do produto
     *
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Recebe preço do produto
     *
     * @param float $price
     * @return void
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }
}
