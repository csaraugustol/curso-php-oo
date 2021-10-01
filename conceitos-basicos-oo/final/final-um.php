<?php

//Final evita com que a classe seja extendida
final class User
{
    private $name;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}

class Administrator extends User
{

}

$administrator = new Administrator();
$administrator->setName('Cesar');

print $administrator->getName();
