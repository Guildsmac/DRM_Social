<?php

class FolderCreator{
    public function getNewFolder($path, $folderName){
        $cont = 1;
        $actFolder = $path . "\\" . $folderName . "#" . $cont;
        if(file_exists($actFolder)){
            while(file_exists($actFolder))
                $actFolder = $path . "\\" . $folderName . "#" . ++$cont;
        }
        mkdir($actFolder, 0777);
        return $actFolder;
    }

}