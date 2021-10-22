<?php

namespace associoation;

class Book
{
    /**
     * @var int
     */
    private $isbn;

    /**
     * @var string
     */
    private $title;

    /**
     * @var Publishing
     */
    private $publishing;

    public function getIsbn(): int
    {
        return $this->isbn;
    }

    public function setIsbn(int $isbn): void
    {
        $this->isbn = $isbn;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getPublishing(): Publishing
    {
        return $this->publishing;
    }

    public function setPublishing(Publishing $publishing): void
    {
        $this->publishing = $publishing;
    }
}
