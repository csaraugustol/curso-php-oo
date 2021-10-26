<?php

namespace Instituicao\View;

class View
{

    private $view;
    private $data = [];

    public function __construct($view)
    {
        $this->view = $view;
    }

    public function __set($index, $value)
    {
        $this->data[$index] = $value;
    }

    public function __get($index)
    {
        return $this->data[$index];
    }

    /**
     * Retorna a rederização das páginas
     *
     * @return string
     */
    public function render(): string
    {
        ob_start();
        require VIEWS_PATH . $this->view;
        return ob_get_clean();
    }
}
