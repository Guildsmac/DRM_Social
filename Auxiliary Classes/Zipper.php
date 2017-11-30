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
        $this->zipFile();

    }

    private function unZipFile(){
        $zip = new ZipArchive;
        $zip->open($this->zippedFile);
        $zip->extractTo($this->unZippedFolder);
        $zip->close();

    }
    //TERMINAR A FUNÇÃO DE ZIPPAR
    private function zipFile(){
        $zip = new ZipArchive;
        if($zip->open($this->unZippedFolder . '\\' . pathinfo($this->zippedFile, PATHINFO_FILENAME). '.zip', ZipArchive::CREATE)===TRUE)
            $this->addFolderToZip($this->unZippedFolder, $zip, '');

        echo "numFile:" . $zip->numFiles . "\n";
        $zip->close();
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

    private function echoTest(){
        echo '<br>TEST<br>';
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