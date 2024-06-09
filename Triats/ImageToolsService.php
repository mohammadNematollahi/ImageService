<?php

//namespace = ?;

trait ImageToolsService
{
    private static $image;
    private static $exclusiveDirectory;
    private static $imageDirectory;
    private static $imageName;
    private static $imageFormat;
    private static $finalImageDirectory;
    private static $finalImageName;

    public function setImage($image): self
    {
        self::$image = $image;
        return new self;
    }

    public function getImage()
    {
        return self::$image;
    }

    public function setExclusiveDirectory($exclusiveDirectory): self
    {
        self::$exclusiveDirectory = trim($exclusiveDirectory, "/\\");
        return new self;
    }

    public function getExclusiveDirectory()
    {
        return self::$exclusiveDirectory;
    }

    public function setImageDirectory($imageDirectory): self
    {
        self::$imageDirectory = trim($imageDirectory, "/\\");
        return new self;
    }

    public function getImageDirectory()
    {
        return self::$imageDirectory;
    }

    public function setImageName($imageName): self
    {
        self::$imageName = $imageName;
        return new self;
    }

    public function getImageName()
    {
        return self::$imageName;
    }

    public function setImageFormat($imageFormat): self
    {
        self::$imageFormat = $imageFormat;
        return new self;
    }

    public function getImageFormat()
    {
        return self::$imageFormat;
    }

    public function setFinalImageDirectory($finalImageDirectory): self
    {
        self::$finalImageDirectory = $finalImageDirectory;
        return new self;
    }

    public function getFinalImageDirectory()
    {
        return self::$finalImageDirectory;
    }

    public function setFinalImageName($finalImageName): self
    {
        self::$finalImageName = $finalImageName;
        return new self;
    }

    public function getFinalImageName()
    {
        return self::$finalImageName;
    }

    public function setOriginalName(): self
    {
        if (!empty(self::$image)) {
            $this->setImageName(pathinfo(self::$image->getClientOriginalName(), PATHINFO_FILENAME));
        }
        return new self;
    }

    public function checkDirectory($fileDirectory)
    {
        if (!file_exists($fileDirectory)) {
            mkdir($fileDirectory, 0777, true);
        }
    }

    public function getImageAddress(): string
    {
        return self::$finalImageDirectory . DIRECTORY_SEPARATOR . self::$finalImageName;
    }

    protected function provider(): self
    {
        // Set properties
        self::$imageDirectory ?? $this->setImageDirectory(date("Y") . DIRECTORY_SEPARATOR . date("M") . DIRECTORY_SEPARATOR . date("d"));
        self::$imageName ?? $this->setImageName(time());
        self::$imageFormat ?? $this->setImageFormat(self::$image->extension());


        // Set final image directory
        $finalImageDirectory = empty(self::$exclusiveDirectory) ? self::$imageDirectory : self::$exclusiveDirectory . DIRECTORY_SEPARATOR . self::$imageDirectory;
        $this->setFinalImageDirectory($finalImageDirectory);

        // Set final image name
        $this->setFinalImageName(self::$imageName . "." . self::$imageFormat);

        // Check final image directory
        $this->checkDirectory(self::$finalImageDirectory);

        return new self;
    }
}
