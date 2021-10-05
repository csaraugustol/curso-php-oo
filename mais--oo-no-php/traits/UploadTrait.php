<?php

trait UploadTrait
{
    public function doUpload($file)
    {
        return true;
    }
}

class Product
{
    use UploadTrait;
}

class Profile
{
    use UploadTrait;
}

$product = new Product();
print $product->doUpload('arquivo...');

print '<br>';

$profile = new Profile();
print $profile->doUpload('arquivo...');
