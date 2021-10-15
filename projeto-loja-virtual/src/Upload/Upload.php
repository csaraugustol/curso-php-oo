<?php

namespace LojaVirtual\Upload;

class Upload
{
    private $folder = '';

    public function setFolder($folder)
    {
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $this->folder = $folder;
    }

    public function doUpload($files = [])
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

    private function renameImage($imageName)
    {
        return sha1($imageName . uniqid() . time());
    }
}
