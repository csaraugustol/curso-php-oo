<?php

use associoation\Book;
use associoation\Publishing;

require __DIR__ . '/Publishing.php';
require __DIR__ . '/Book.php';

$publishing = new Publishing();
$publishing->setName("Editora Vex");
$publishing->setId(1);

$book = new Book();
$book->setTitle('Livro Teste');
$book->setIsbn('11.2211.22.11');
$book->setPublishing($publishing);

print $book->getPublishing()->getName();
