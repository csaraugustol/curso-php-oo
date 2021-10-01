<?php

//public    -> Acessa de qualquer lugar
//protected -> Acessa somente dentro da classe ou extendido
//private   -> Acessa somente da instância ou própia classe

class Person
{
    public $name;
    // protected $agePerson = 27;
    private $agePerson = 27;

    public function showName()
    {
        return $this->name;
    }

    public function showAgePerson()
    {
        return $this->agePerson;
    }
}

class Woman extends Person
{
    public function showWomanAge()
    {
        // return $this->agePerson; //Com protected
        return $this->showAgePerson(); //Com private
    }
}

$person = new Person();
$person->name = 'Zeca';
// $person->agePerson = 19;

print $person->name;
print "\n";

$woman = new Woman();
print $woman->showWomanAge();
