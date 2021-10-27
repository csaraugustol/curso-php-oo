<?php

namespace composition;

use composition\Episode;

class Season
{
    /**
     * @var Episode
     */
    private $episode;

    /**
     * @var string
     */
    private $seasonTitle;

    /**
     * Recebe o título da temporada por parâmetro
     *
     * @param string $seasonTitle
     */
    public function __construct(string $seasonTitle)
    {
        $this->episode = new Episode();
        $this->seasonTitle = $seasonTitle;
    }

    /**
     * Recebe o título e descrição do episódio
     * relacionado a temporada
     *
     * @param string $title
     * @param string $description
     * @return void
     */
    public function setEpisode(string $title, string $description): void
    {
        $this->episode->setTitle($title);
        $this->episode->setDescription($description);
    }

    /**
     * Retorna um episódio da temporada
     *
     * @return Episode
     */
    public function getEpisode(): Episode
    {
        return $this->episode;
    }
}
