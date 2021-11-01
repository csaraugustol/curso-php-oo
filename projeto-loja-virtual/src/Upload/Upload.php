<?php

namespace LojaVirtual\Upload;

class Upload
{
    /**
     * Caminho das imagens
     *
     * @var string
     */
    private $folder = '';

    /**
     * Verifica se existe pasta e se não existir
     * a pasta será criada junto com seu caminho
     *
     * @param string $folder
     * @return void
     */
    public function setFolder(string $folder): void
    {
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $this->folder = $folder;
    }

    /**
     * Faz upload das imagens realacionadas aos produtos
     *
     * @param array $files
     * @return array
     */
    public function doUpload(array $files = []): array
    {
        $arrayImagesName = [];
        for ($i = 0; $i < count($files['name']); $i++) {
            $extension = strrchr($files['name'][$i], '.');
            $newImageName = $this->renameImage($files['name'][$i]) . $extension;

            if (move_uploaded_file($files['tmp_name'][$i], $this->folder . $newImageName)) {
                $arrayImagesName[] = $newImageName;
            }
        }

        return $arrayImagesName;
    }

    /**
     * Renomea imagem para evitar repetição de nomes
     *
     * @param string $imageName
     * @return string
     */
    private function renameImage(string $imageName): string
    {
        return sha1($imageName . uniqid() . time());
    }
}
