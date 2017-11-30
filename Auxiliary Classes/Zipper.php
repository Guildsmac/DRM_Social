<?php
include_once 'FolderCreator.php';

class Zipper{
    private $actFolder = null,
           $zippedFile = null,
           $unZippedFolder = null,
           $originalBookName = null;

    public function __construct($filePath){
        $this->originalBookName = pathinfo($filePath, PATHINFO_BASENAME);
        if (!file_exists('temp'))
            mkdir('temp', '0777');
        $folderCreator = new FolderCreator();
        $this->actFolder = $folderCreator->getNewFolder('temp', 'temporary');
        $this->copyFile($filePath);
        rename($this->actFolder . '\\' . pathinfo($filePath, PATHINFO_BASENAME),
                $this->actFolder . '\\' . pathinfo($filePath, PATHINFO_FILENAME) . '.' . 'zip');

        $this->zippedFile = $this->actFolder . '\\' . pathinfo($filePath, PATHINFO_FILENAME) . '.' . 'zip';
        $this->unZippedFolder = $this->actFolder . '\\' . 'extractedFiles';
        mkdir($this->unZippedFolder, 0777);
        $this->unZipFile();

    }

    private function unZipFile(){
        $zip = new ZipArchive;
        $zip->open($this->zippedFile);
        $zip->extractTo($this->unZippedFolder);
        $zip->close();

    }
    //TERMINAR A CLASSE DE ZIPPAR
    private function zipFile($rootPath){
        $rootPath = realpath($rootPath);
        $zip = new ZipArchive;
        $zip->open(pathinfo($this->zippedFile, BASENAME), ZipArchive::CREATE | ZipArchive::OVERWRITE);



    }

    public function getUnzippedFolder(){
        return $this->unZippedFolder;
    }

    private function copyFile($filePath){
        $fileName = pathinfo($filePath, PATHINFO_BASENAME);
        $file = fopen($this->actFolder . '\\' . $fileName, 'w');
        copy($filePath, $this->actFolder . '\\' . $fileName);
        fclose($file);

    }
}