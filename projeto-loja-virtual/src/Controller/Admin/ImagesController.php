<?php

namespace LojaVirtual\Controller\Admin;

use Exception;
use LojaVirtual\Session\Flash;
use LojaVirtual\DataBase\Connection;
use LojaVirtual\Entity\ProductImage;

class ImagesController
{
    /**
     * Remove uma imagem do produto
     *
     * @param integer $id
     * @return redirect
     */
    public function remove(int $id)
    {
        try {
            $image = new ProductImage(Connection::getInstance());
            $imageData = $image->findById($id);

            if (file_exists($file = UPLOAD_PATH . '/products/' . $imageData['image'])) {
                unlink($file);
            }

            $image->delete($id);

            Flash::sendMessageSession('success', 'Imagem removida com sucesso!');
        } catch (Exception $exception) {
            Flash::returnExceptionErrorMessage(
                $exception,
                'Ocorreu um erro interno ao remover imagem, por favor contate o administrador.'
            );

            return header('Location: ' . HOME . '/admin/products/edit/' . $imageData['product_id']);
        }

        return header('Location: ' . HOME . '/admin/products/edit/' . $imageData['product_id']);
    }
}
