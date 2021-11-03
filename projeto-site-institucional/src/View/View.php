<?php

namespace Instituicao\View;

class View
{
    /**
     * Caminho para o pacote da view que foi solicitada no controller
     *
     * @var string
     */
    private $view;

    /**
     * Dados recebidos
     *
     * @var array
     */
    private $data = [];

    /**
     * Rebece a view solicitada por parâmetro
     *
     * @param string $view
     */
    public function __construct(string $view)
    {
        $this->view = $view;
    }

    /**
     * Recebe o índice e um array para carregar
     * os dados em tela
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
     * Retorna o array para listagem na view
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
     * @return string
     */
    public function render(): string
    {
        ob_start();
        require VIEWS_PATH . $this->view;
        return ob_get_clean();
    }
}
