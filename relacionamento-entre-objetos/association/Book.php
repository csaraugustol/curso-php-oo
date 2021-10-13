<?php

class Book
{
    private $isbn;
    private $title;
    private $publishing;

    public function getIsbn()
    {
        return $this->isbn;
    }

    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getPublishing()
    {
        return $this->publishing;
    }

    public function setPublishing($publishing)
    {
        $this->publishing = $publishing;
    }
}
