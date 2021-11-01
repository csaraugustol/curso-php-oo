<?php

namespace LojaVirtual\Controller\Admin;

use Exception;
use LojaVirtual\View\View;
use LojaVirtual\Session\Flash;
use LojaVirtual\Upload\Upload;
use LojaVirtual\Entity\Product;
use LojaVirtual\Entity\Category;
use LojaVirtual\DataBase\Connection;
use LojaVirtual\Entity\ProductImage;
use Ausi\SlugGenerator\SlugGenerator;
use LojaVirtual\Entity\ProductCategory;
use LojaVirtual\Security\Validator\Sanitizer;
use LojaVirtual\Security\Validator\Validator;

class ProductsController
{
    public function index()
    {
        $view = new View('admin/products/index.phtml');
        $view->products = (new Product(Connection::getInstance()))->findAll();
        return $view->render();
    }

    public function new()
    {
        $product = new Product(Connection::getInstance());

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $data = $_POST;
                $images = $_FILES['images'];
                $categories = $data['categories'];
                //unset($data['categories']);


                $data = Sanitizer::sanitizeData($data, Product::$filters);

                if (!Validator::validateRequiredFields($data)) {
                    Flash::sendMessageSession('warning', 'Preencha todos os campos!');
                    return header('Location: ' . HOME . '/admin/products/new');
                }

                $data['slug'] = (new SlugGenerator())->generate($data['name']);
                $data['price'] = str_replace('.', '', $data['price']);
                $data['price'] = str_replace(',', '.', $data['price']);
                $data['is_active'] = $data['is_active'] === 'A' ? 1 : 0;
                $productId = $product->insert($data);
                if (!$productId) {
                    Flash::sendMessageSession('error', 'Erro ao criar produto!');
                    return header('Location: ' . HOME . '/admin/products/new');
                }

                if (isset($images['name'][0]) && $images['name'][0]) {
                    if (!Validator::validateImagesFileType($images)) {
                        Flash::sendMessageSession('danger', 'Formato de imagem inválido!');
                        return header('Location: ' . HOME . '/admin/products/new');
                    }

                    $upload = new Upload();
                    $upload->setFolder(UPLOAD_PATH . '/products/');
                    $images = $upload->doUpload($images);

                    foreach ($images as $image) {
                        $imagesData = [];
                        $imagesData['image'] = $image;
                        $imagesData['product_id'] = $productId;
                        $productImagens = new ProductImage(Connection::getInstance());
                        $productImagens->insert($imagesData);
                    }
                }

                if (count($categories)) {
                    foreach ($categories as $category) {
                        $productCategory = new ProductCategory(Connection::getInstance());
                        $productCategory->insert([
                            'product_id'  => $productId,
                            'category_id' => $category
                        ]);
                    }
                }

                Flash::sendMessageSession('success', 'Produto criado com sucesso!');
                return header('Location: ' . HOME . '/admin/products');
            } catch (Exception $exception) {
                if (APP_DEBUG) {
                    Flash::sendMessageSession('warning', $exception->getMessage());
                    return header('Location: ' . HOME . '/admin/products/new');
                }
                Flash::sendMessageSession('error', 'Erro ao salvar produto!');
                return header('Location: ' . HOME . '/admin/products/new');
            }
        }
        $view = new View('admin/products/new.phtml');
        $view->categories = (new Category(Connection::getInstance()))->findAll();
        return $view->render();
    }

    public function edit($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;

            try {
                $images = $_FILES['images'];
                $categories = $data['categories'];

                $data = Sanitizer::sanitizeData($data, Product::$filters);

                if (!Validator::validateRequiredFields($data)) {
                    Flash::sendMessageSession('warning', 'Preencha todos os campos!');
                    return header('Location: ' . HOME . '/admin/products/edit/' . $id);
                }

                $data['id'] = (int)$id;
                $data['price'] = str_replace('.', '', $data['price']);
                $data['price'] = str_replace(',', '.', $data['price']);
                $data['is_active'] = $data['is_active'] === 'A' ? 1 : 0;

                $product = new Product(Connection::getInstance());

                if (!$product->update($data)) {
                    Flash::sendMessageSession('error', 'Erro ao editar produto!');
                    return header('Location: ' . HOME . '/admin/products/edit/' . $id);
                }

                $productCategory = new ProductCategory(Connection::getInstance());
                $productCategory->sync($id, $categories);

                if (isset($images['name'][0]) && $images['name'][0]) {
                    if (!Validator::validateImagesFileType($images)) {
                        Flash::sendMessageSession('danger', 'Formato de imagem inválido!');
                        return header('Location: ' . HOME . '/admin/products/edit/' . $id);
                    }

                    $upload = new Upload();
                    $upload->setFolder(UPLOAD_PATH . '/products/');
                    $images = $upload->doUpload($images);

                    foreach ($images as $image) {
                        $imagesData = [];
                        $imagesData['image'] = $image;
                        $imagesData['product_id'] = $id;
                        $productImagens = new ProductImage(Connection::getInstance());
                        $productImagens->insert($imagesData);
                    }
                }

                Flash::sendMessageSession('success', 'Produto editado com sucesso!');
                return header('Location: ' . HOME . '/admin/products');
            } catch (Exception $exception) {
                if (APP_DEBUG) {
                    Flash::sendMessageSession('warning', $exception->getMessage());
                    return header('Location: ' . HOME . '/admin/products/edit/' . $id);
                }
                Flash::sendMessageSession('error', 'Erro ao editar produto!');
                return header('Location: ' . HOME . '/admin/products/edit/' . $id);
            }
        }
        $view = new View('admin/products/edit.phtml');
        $view->product = (new Product(Connection::getInstance()))->returnProductWithImages($id);

        $view->productCategories = (new ProductCategory(Connection::getInstance()))
            ->filterWithConditions(['product_id' => $id]);
        $view->productCategories = array_map(function ($line) {
            return $line['category_id'];
        }, $view->productCategories);

        $view->categories = (new Category(Connection::getInstance()))->findAll();
        return $view->render();
    }

    public function remove($id = null)
    {
        try {
            $post = new Product(Connection::getInstance());

            if (!$post->delete($id)) {
                Flash::sendMessageSession('error', 'Erro ao realizar remoção do produto!');
                return header('Location: ' . HOME . '/categories');
            }

            Flash::sendMessageSession('success', 'Produto removido com sucesso!');
            return header('Location: ' . HOME . '/admin/products');
        } catch (Exception $exception) {
            if (APP_DEBUG) {
                Flash::sendMessageSession('error', $exception->getMessage());
                return header('Location: ' . HOME . '/admin/products');
            }
            Flash::sendMessageSession('error', 'Ocorreu ao deletar pŕoduto.');
            return header('Location: ' . HOME . '/admin/products');
        }
    }
}
