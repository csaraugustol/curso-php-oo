<?php

namespace associoation;

class Publishing
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
     * Retorna o id da publicação
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Recebe o id da publicação
     *
     * @param int $id
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Retorna o nome da publicação
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Recebe o nome da publicação
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
