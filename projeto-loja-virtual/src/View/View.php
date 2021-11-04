<?php

namespace LojaVirtual\View;

class View
{
    /**
     * Caminho para o pacote da view que foi solicitada no controller
     *
     * @var string
     */
    private $view;

    /**
     * Dados retornados
     *
     * @var array
     */
    private $data = [];

    /**
     * Recebe a View desejada
     *
     * @param string $view
     */
    public function __construct(string $view)
    {
        $this->view = $view;
    }

    /**
     * Recebe uma variável e seus dados
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
     * Realiza retorno do caminho da URL
     *
     * @param string $index
     * @return array
     */
    public function __get(string $index): array
    {
        return $this->data[$index];
    }

    /**
     * Faz a view ser renderizada
     *
     * @return void
     */
    public function render(): string
    {
        ob_start();
        require VIEWS_PATH . $this->view;
        return ob_get_clean();
    }
}
