<?php

trait MyTraitOne
{
    public function hello()
    {
        return 'Hello World';
    }

    protected function testMethod()
    {
        return 'Test';
    }
}

trait MyTraitTwo
{
    public function showName($name)
    {
        return 'Hello ' . $name;
    }

    public function hello()
    {
        return 'Hello World 2';
    }
}

class Client
{
    use MyTraitOne, MyTraitTwo {
        MyTraitTwo::hello insteadof MyTraitOne;
        MyTraitOne::hello as helloMy;
        //Abaixo estou modificando a visibilidade do método da trait MyTraitOne
        MyTraitOne::hello as private helloVisibilityChanged;
    }

    public function changedMethod()
    {
        print $this->testMethod();
        return $this->helloVisibilityChanged();
    }
}

$client = new Client();
print $client->hello(); //Método hello vindo da MyTraitTwo 
print '<br>';
print $client->helloMy(); //Alias para o método hello da MyTrait
print '<br>';
print $client->showName('Cesar');
