<?php

// EXEMPLO DE SOBRECARGA
abstract class Printer
{
    public function toPrint()
    {
        return "Printing data...";
    }
}

class HpPrinter extends Printer
{
    public function toPrint()
    {
        return "HP printing data...";
    }
}

class EpsonPrinter extends Printer
{
    public function toPrint()
    {
        return "Epson printing data...";
    }
}

$printer = new HpPrinter();
print $printer->toPrint();



// EXEMPLO DE SOBREPOSIÇÃO
abstract class Animal
{
    abstract public function sound();
}

class Dog extends Animal
{
    // PHP suporta polimorfismo de sobreposição
    public function sound()
    {
        return "Au au au...";
    }

    // PHP não suporta polimorfismo de sobrecarga
    public function react()
    {

    }

    // PHP não suporta polimorfismo de sobrecarga
    public function react($owner)
    {

    }

    // PHP não suporta polimorfismo de sobrecarga
    public function react($owner, $food)
    {

    }
}
