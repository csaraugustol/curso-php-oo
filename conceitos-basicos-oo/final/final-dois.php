<?php

class User
{
    private $name;

    public function getName()
    {
        return $this->name;
    }

    // Evita também a sobrescrita de métodos
    final public function setName($name)
    {
        $this->name = $name;
    }
}

class Administrator extends User
{
    public function setName($name)
    {
        return 'Teste';
    }
}

$administrator = new Administrator();
$administrator->setName('Cesar');

print $administrator->getName();
