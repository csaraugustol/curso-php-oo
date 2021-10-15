<?php

namespace LojaVirtual\Controller\Admin;

use Exception;
use LojaVirtual\Session\Flash;
use LojaVirtual\DataBase\Connection;
use LojaVirtual\Entity\ProductImage;

class ImagesController
{
    public function remove($id)
    {
        try {
            $image = new ProductImage(Connection::getInstance());
            $imageData = $image->findById($id);

            if (file_exists($file = UPLOAD_PATH . '/products/' . $imageData['image'])) {
                unlink($file);
            }

            $image->delete($id);

            Flash::sendMessageSession('success', 'Imagem removida com sucesso!');
            return header('Location: ' . HOME . '/admin/products/edit/' . $imageData['product_id']);
        } catch (Exception $exception) {
            if (APP_DEBUG) {
                Flash::sendMessageSession('error', $exception->getMessage());
                return header('Location: ' . HOME . '/admin/products/edit/' . $imageData['product_id']);
            }
            Flash::sendMessageSession('error', 'Ocorreu um problema interno, por favor contacte o admin.');
            return header('Location: ' . HOME . '/admin/products/edit/' . $imageData['product_id']);
        }
    }
}
