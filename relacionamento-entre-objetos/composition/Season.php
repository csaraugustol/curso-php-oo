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

    public function __construct($seasonTitle)
    {
        $this->episode = new Episode();
        $this->seasonTitle = $seasonTitle;
    }

    public function setEpisode($title, $description): void
    {
        $this->episode->setTitle($title);
        $this->episode->setDescription($description);
    }

    public function getEpisode(): Episode
    {
        return $this->episode;
    }
}
