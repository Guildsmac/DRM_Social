<?php
include_once "C:\\Users\\Guildsmac\\PhpstormProjects\\DRM Social\\Book Modifier\\BookWriter.php";

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

    public function getExtractedFolder(){
        return $this->unZippedFolder;
    }

    private function unZipFile(){
        $zip = new ZipArchive;
        $zip->open($this->zippedFile);
        $zip->extractTo($this->unZippedFolder);
        $zip->close();

    }

    public function zipFile($newFolder){
        $zip = new ZipArchive;
        if($zip->open($newFolder . '\\' . pathinfo($this->zippedFile, PATHINFO_FILENAME). '.zip', ZipArchive::CREATE)===TRUE)
            $this->addFolderToZip($newFolder, $zip, '');

        $zip->close();

        if (!file_exists('Complete Books'))
            mkdir('Complete Books', '0777');
        $folderCreator = new FolderCreator();
        $newPath = $folderCreator->getNewFolder('Complete Books', 'book');

        rename($newFolder . '\\' . pathinfo($this->zippedFile, PATHINFO_BASENAME),
            $newPath . '\\' . pathinfo($this->zippedFile, PATHINFO_FILENAME) . '.epub');
    }

    private function addFolderToZip($dir, $zipArchive, $zipdir = ''){
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                //Add the directory
                if(!empty($zipdir))
                    $zipArchive->addEmptyDir($zipdir);

                // Loop through all the files
                while (($file = readdir($dh)) !== false) {
                    //If it's a folder, run the function again!
                    if(!is_file($dir . '\\' .$file)){
                        // Skip parent and root directories
                        if(($file !== ".") && ($file !== ".."))
                            $this->addFolderToZip(
                                empty($dir) ? $file : $dir . '\\' . $file
                                , $zipArchive,
                                empty($zipdir) ? $file : $zipdir . '\\' . $file);


                    }else
                        // Add the files
                        $zipArchive->addFile(
                            empty($dir) ? $file : $dir . '\\' . $file,
                            empty($zipdir) ? $file : $zipdir .'\\'. $file);
                }
            }
        }
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