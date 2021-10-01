<?php

class Product
{
    private $name;
    private $price;
    private $description;
    private $category;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }
}

$product = new Product();
$product->setName('Risoles');
$product->setPrice(4.90);
$product->setDescription('Risoles de carne moída');
$product->setCategory('Salgado');

print $product->getName();
print "\n";
print $product->getPrice();
print "\n";
print $product->getDescription();
print "\n";
print $product->getCategory();
