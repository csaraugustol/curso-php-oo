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

    /**
     * Retorna código do livro
     *
     * @return int
     */
    public function getIsbn(): int
    {
        return $this->isbn;
    }

    /**
     * Recebe código do livro
     *
     * @param int $isbn
     * @return void
     */
    public function setIsbn(int $isbn): void
    {
        $this->isbn = $isbn;
    }

    /**
     * Retorna título do livro
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Recebe título do livro
     *
     * @param string $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Retorna uma publicação relacionada ao livro
     *
     * @return Publishing
     */
    public function getPublishing(): Publishing
    {
        return $this->publishing;
    }

    /**
     * Recebe uma publicação relacionada ao livro
     *
     * @param Publishing $publishing
     * @return void
     */
    public function setPublishing(Publishing $publishing): void
    {
        $this->publishing = $publishing;
    }
}
