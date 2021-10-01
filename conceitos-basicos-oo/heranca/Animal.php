<?php

class Animal
{

    public $name;

    public function sleep()
    {
        return $this->name . ' are sleeping...';
    }
}

class Dog extends Animal
{
    public $name = 'Zezim'; //Sobrescreve o nome que trago
    public function sleep()
    {
        print parent::sleep() . "\n"; //Mensagem que vem do pai
        return 'Dog are sleeping';  //Sobrescreve o método do pai
    }
}

class Bird extends Animal
{

}

$dog = new Dog();
//$dog->name = 'Ted';

print $dog->sleep();

print "\n";

$bird = new Bird();
$bird->name = 'Bird';

print $bird->sleep();
