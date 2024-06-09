<?php

//namespace = ?;

use CallMethod;
use ImageToolsService;
use Intervention\Image\Facades\Image as ImagePackage;

class Upload
{
    use ImageToolsService , CallMethod;

    public function save()
    {
        // listener provider
        $this->provider();

        $this->saveGif();

        $result = ImagePackage::make(self::$image->getRealPath())->save(public_path($this->getImageAddress()), null, $this->getImageFormat());

        return $result ? $this->getImageAddress() : false;
    }

    private function saveGif()
    {
        if($this->getImageFormat() == "gif"){

            $this->provider();
    
            copy($this->getImage(), public_path($this->getImageAddress()));
            return $this->getImageAddress();
        }
    }

    protected function resizeAndSave($width, $height)
    {
        //listener provider
        $this->provider();

        //save image

        $result = Image::make(self::$image->getRealPath())->fit($width, $height)->save(public_path($this->getImageAddress()), null, $this->getImageFormat());

        return $result ? $this->getImageAddress() : false;
    }

    protected function createIndexAndSave()
    {

        //get data from config
        $imageSizes = Config::get("imag.index-image-size");

        //set directory
        $this->getImageDirectory() ?? $this->setImageDirectory(date("Y") . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR . date('d'));
        $this->setImageDirectory($this->getImageDirectory() . DIRECTORY_SEPARATOR . time());

        //set name
        $this->getImageName() ?? $this->setImageName(time());
        $imageName = $this->getImageName();

        $imageIndex = [];
        foreach ($imageSizes as $key => $value) {

            //set full image name
            $currentImageName = $imageName . "_" . $key;
            $this->setImageName($currentImageName);

            //use provider
            $this->provider();

            //save images
            $result = Image::make(self::$image->getRealPath())->fit($imageSizes['width'], $imageSizes['height'])->save(public_path
            ($this->getImageAddress()), null, $this->getImageFormat());

            if ($result) {
                $imageIndex[$key] = $this->getImageAddress();
            } else {
                return false;
            }

        }

        $images["indexArray"] = $imageIndex;
        $images["directory"] = $this->getFinalImageDirectory();
        $images["currentImage"] = Config::get('image.default-current-index-image');

        return $images;

    }

    protected function deleteImage($path)
    {
        if (file_exists($path)) {
            unlink($path);
        }
        return false;
    }

    protected function deleteImageIndex($images)
    {
        $directory = public_path($images['directory']);
        $this->deleteDirectoryAndFiles($directory);
    }


    protected function deleteDirectoryAndFiles($directory)
    {

        if (!is_dir($directory)) {
            return false;
        }

        $files = glob($directory . DIRECTORY_SEPARATOR . "*", GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->deleteDirectoryAndFiles($file);
            } else {
                unlink($file);
            }
        }

        $final = rmdir($directory);
        return $final;
    }
}