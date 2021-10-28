<?php

namespace Blog\View;

class View
{
    /**
     * Caminho da URL
     *
     * @var string
     */
    private $view;

    /**
     * Array para carregar dados
     *
     * @var array
     */
    private $data = [];

    /**
     * Recebe caminho da URL por parâmetro
     *
     * @param string $view
     */
    public function __construct(string $view)
    {
        $this->view = $view;
    }

    /**
     * Recebe a entidade e seus dados
     *
     * @param string $index
     * @param array $value
     * @return void
     */
    public function __set(string $index, array $value): void
    {
        $this->data[$index] = $value;
    }

    /**
     * Retorna o caminho da URL
     *
     * @param string $index
     * @return array
     */
    public function __get(string $index): array
    {
        return $this->data[$index];
    }

    /**
     * Executa as renderizações das páginas
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
